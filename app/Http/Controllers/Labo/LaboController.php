<?php
namespace App\Http\Controllers\Labo;

use App\Http\Controllers\Controller;
use App\Models\DemandeLabo;
use App\Models\ExamenLabo;
use App\Models\ResultatLabo;
use App\Models\Patient;
use Illuminate\Http\Request;

class LaboController extends Controller
{
    public function index()
    {
        $demandesEnAttente = DemandeLabo::with(['patient', 'medecin'])
            ->whereIn('statut', ['en_attente', 'preleve', 'en_cours'])
            ->orderByRaw("CASE urgence WHEN 'tres_urgent' THEN 1 WHEN 'urgent' THEN 2 WHEN 'normal' THEN 3 ELSE 4 END")
            ->orderBy('date_demande')
            ->get();

        $demandesTerminees = DemandeLabo::with(['patient', 'medecin'])
            ->where('statut', 'termine')
            ->orderBy('date_resultat', 'desc')
            ->limit(20)
            ->get();

        $stats = [
            'en_attente' => DemandeLabo::where('statut', 'en_attente')->count(),
            'en_cours' => DemandeLabo::whereIn('statut', ['preleve', 'en_cours'])->count(),
            'terminees_jour' => DemandeLabo::where('statut', 'termine')->whereDate('date_resultat', today())->count(),
            'urgentes' => DemandeLabo::whereIn('statut', ['en_attente', 'preleve', 'en_cours'])->where('urgence', '!=', 'normal')->count(),
        ];

        $examens = ExamenLabo::where('actif', true)->orderBy('categorie')->orderBy('nom')->get();
        $patients = Patient::orderBy('nom')->get();
        $medecins = \App\Models\Medecin::where('statut', 'actif')->orderBy('nom')->get();

        return view('labo.index', compact('demandesEnAttente', 'demandesTerminees', 'stats', 'examens', 'patients', 'medecins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'examens' => 'required|array|min:1',
            'examens.*' => 'exists:examens_labo,id',
            'urgence' => 'required|in:normal,urgent,tres_urgent',
            'notes_cliniques' => 'nullable|string',
        ]);

        $numero = 'LAB-' . date('Ymd') . '-' . str_pad(DemandeLabo::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        $demande = DemandeLabo::create([
            'numero' => $numero,
            'patient_id' => $validated['patient_id'],
            'medecin_id' => $validated['medecin_id'],
            'consultation_id' => $validated['consultation_id'] ?? null,
            'date_demande' => today(),
            'statut' => 'en_attente',
            'urgence' => $validated['urgence'],
            'notes_cliniques' => $validated['notes_cliniques'],
        ]);

        foreach ($validated['examens'] as $examenId) {
            $examen = ExamenLabo::find($examenId);
            ResultatLabo::create([
                'demande_labo_id' => $demande->id,
                'examen_labo_id' => $examenId,
                'unite' => $examen->unite,
                'valeur_reference' => $examen->valeur_normale,
            ]);
        }

        return redirect()->back()->with('success', 'Demande de laboratoire ' . $numero . ' creee');
    }

    public function updateStatut(Request $request, DemandeLabo $demande)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,preleve,en_cours,termine,annule',
        ]);

        $data = ['statut' => $validated['statut']];
        if ($validated['statut'] === 'termine') {
            $data['date_resultat'] = today();
            $data['realise_par'] = auth()->id();
        }

        $demande->update($data);
        return redirect()->back()->with('success', 'Statut mis a jour');
    }

    public function saisirResultats(Request $request, DemandeLabo $demande)
    {
        $validated = $request->validate([
            'resultats' => 'required|array',
            'resultats.*.id' => 'required|exists:resultats_labo,id',
            'resultats.*.valeur' => 'nullable|string',
            'resultats.*.interpretation' => 'nullable|in:normal,bas,eleve,critique',
            'resultats.*.commentaire' => 'nullable|string',
        ]);

        foreach ($validated['resultats'] as $res) {
            ResultatLabo::where('id', $res['id'])->update([
                'valeur' => $res['valeur'] ?? null,
                'interpretation' => $res['interpretation'] ?? null,
                'commentaire' => $res['commentaire'] ?? null,
            ]);
        }

        $demande->update(['statut' => 'termine', 'date_resultat' => today(), 'realise_par' => auth()->id()]);

        // Check for critical values and alert ordering doctor
        $critiques = $demande->resultats()->where('interpretation', 'critique')->with('examen')->get();
        if ($critiques->count() > 0) {
            $alertMsg = $critiques->map(fn($r) => $r->examen->nom . ': ' . $r->valeur)->implode(', ');

            // Notify ordering doctor
            if ($demande->medecin->user) {
                $demande->medecin->user->notify(new \App\Notifications\ResultatCritique($demande, $alertMsg));
            }
            // Notify admin
            \App\Models\User::where('role', 'admin')->each(fn($u) => $u->notify(new \App\Notifications\ResultatCritique($demande, $alertMsg)));
        }

        return redirect()->back()->with('success', 'Resultats enregistres');
    }

    public function show(DemandeLabo $demande)
    {
        $demande->load(['patient', 'medecin', 'resultats.examen', 'realisePar']);
        return response()->json([
            'id' => $demande->id,
            'numero' => $demande->numero,
            'patient' => $demande->patient->prenom . ' ' . $demande->patient->nom,
            'medecin' => 'Dr. ' . $demande->medecin->prenom . ' ' . $demande->medecin->nom,
            'date_demande' => $demande->date_demande->format('d/m/Y'),
            'statut' => $demande->statut,
            'urgence' => $demande->urgence,
            'notes_cliniques' => $demande->notes_cliniques,
            'date_resultat' => $demande->date_resultat?->format('d/m/Y'),
            'realise_par' => $demande->realisePar?->name,
            'resultats' => $demande->resultats->map(fn($r) => [
                'id' => $r->id,
                'examen' => $r->examen->nom,
                'categorie' => $r->examen->categorie,
                'valeur' => $r->valeur,
                'unite' => $r->unite,
                'valeur_reference' => $r->valeur_reference,
                'interpretation' => $r->interpretation,
                'commentaire' => $r->commentaire,
            ]),
        ]);
    }

    public function resultsPdf(DemandeLabo $demande)
    {
        $demande->load(['patient', 'medecin', 'resultats.examen', 'realisePar']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('labo.resultats-pdf', compact('demande'));
        return $pdf->stream('resultats-' . $demande->numero . '.pdf');
    }

    public function examens()
    {
        $examens = ExamenLabo::orderBy('categorie')->orderBy('nom')->get();
        $categories = ExamenLabo::select('categorie')->distinct()->pluck('categorie');
        return view('labo.examens', compact('examens', 'categories'));
    }

    public function storeExamen(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie' => 'required|string|max:100',
            'unite' => 'nullable|string|max:50',
            'valeur_normale' => 'nullable|string|max:100',
            'prix' => 'required|integer|min:0',
        ]);

        ExamenLabo::create($validated);
        return redirect()->back()->with('success', 'Examen ajoute');
    }
}
