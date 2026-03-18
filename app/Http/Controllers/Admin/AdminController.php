<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Chambre;
use App\Models\Hospitalisation;
use App\Models\Patient;
use App\Models\Planning;
use App\Models\Rendezvous;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==================== MÉDECINS ====================

    public function medecins(Request $request)
    {
        $query = Medecin::withCount(['consultations', 'hospitalisations']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'like', "%$s%")
                  ->orWhere('prenom', 'like', "%$s%");
            });
        }

        if ($request->filled('specialite')) {
            $query->where('specialite', $request->specialite);
        }

        $medecins     = $query->orderBy('nom')->get();
        $specialites  = Medecin::select('specialite')->distinct()->pluck('specialite');

        return view('admin.medecins', compact('medecins', 'specialites'));
    }

    public function storeMedecin(Request $request)
    {
        $validated = $request->validate([
            'nom'                => 'required|string|max:100',
            'prenom'             => 'required|string|max:100',
            'specialite'         => 'required|string|max:100',
            'telephone'          => 'required|string|max:20',
            'email'              => 'nullable|email|unique:medecins,email',
            'bureau'             => 'nullable|string|max:50',
            'tarif_consultation' => 'nullable|integer|min:0',
        ]);

        Medecin::create(array_merge($validated, ['statut' => 'disponible']));

        return redirect()->route('admin.medecins')->with('success', 'Médecin ajouté avec succès');
    }

    public function updateMedecin(Request $request, Medecin $medecin)
    {
        $validated = $request->validate([
            'statut' => 'required|in:disponible,en_consultation,absent',
        ]);

        $medecin->update($validated);

        return redirect()->back()->with('success', 'Statut mis à jour');
    }

    // ==================== PLANNING ====================

    public function planning(Request $request)
    {
        $medecins = Medecin::orderBy('nom')->get();

        // Rendez-vous
        $queryRdv = Rendezvous::with(['patient', 'medecin']);

        if ($request->filled('date')) {
            $queryRdv->whereDate('date', $request->date);
        } else {
            $queryRdv->whereDate('date', '>=', today());
        }

        if ($request->filled('medecin_id')) {
            $queryRdv->where('medecin_id', $request->medecin_id);
        }

        $rendezvous = $queryRdv->orderBy('date')->orderBy('heure')->paginate(20);

        // Planning médecins
        $planningMedecin = null;
        if ($request->filled('planning_medecin_id')) {
            $planningMedecin = Planning::with('medecin')
                ->where('medecin_id', $request->planning_medecin_id)
                ->orderByRaw("FIELD(jour, 'lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche')")
                ->get();
        }

        return view('admin.planning', compact('medecins', 'rendezvous', 'planningMedecin'));
    }

    public function storeRendezvous(Request $request)
    {
        $validated = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'medecin_id'  => 'required|exists:medecins,id',
            'date'        => 'required|date|after_or_equal:today',
            'heure'       => 'required',
            'motif'       => 'required|string|max:255',
        ]);

        Rendezvous::create(array_merge($validated, ['statut' => 'en_attente']));

        return redirect()->back()->with('success', 'Rendez-vous créé');
    }

    // ==================== HOSPITALISATION ====================

    public function hospitalisation(Request $request)
    {
        $chambres = Chambre::with('patient')->orderBy('etage')->orderBy('numero')->get();

        $hospitalisations = Hospitalisation::with(['patient', 'chambre', 'medecin'])
            ->where('statut', 'en_cours')
            ->orderBy('date_admission', 'desc')
            ->get();

        $patients  = Patient::orderBy('nom')->get();
        $medecins  = Medecin::where('statut', '!=', 'absent')->orderBy('nom')->get();
        $chambresLibres = $chambres->where('statut', 'libre');

        $stats = [
            'total'       => $chambres->count(),
            'occupees'    => $chambres->where('statut', 'occupee')->count(),
            'libres'      => $chambres->where('statut', 'libre')->count(),
            'maintenance' => $chambres->where('statut', 'maintenance')->count(),
        ];

        return view('admin.hospitalisation', compact(
            'chambres', 'hospitalisations', 'patients', 'medecins', 'chambresLibres', 'stats'
        ));
    }

    public function storeAdmission(Request $request)
    {
        $validated = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'chambre_id'  => 'required|exists:chambres,id',
            'medecin_id'  => 'required|exists:medecins,id',
            'motif'       => 'required|string',
        ]);

        DB::transaction(function () use ($validated) {
            Hospitalisation::create(array_merge($validated, [
                'date_admission' => today(),
                'statut'         => 'en_cours',
            ]));

            Chambre::find($validated['chambre_id'])->update([
                'statut'     => 'occupee',
                'patient_id' => $validated['patient_id'],
            ]);
        });

        return redirect()->back()->with('success', 'Patient admis avec succès');
    }

    // ==================== CAISSE ====================

    public function caisse(Request $request)
    {
        // Stats
        $recettesJour  = Transaction::whereDate('date', today())->where('type', 'entree')->sum('montant');
        $depensesJour  = Transaction::whereDate('date', today())->where('type', 'sortie')->sum('montant');
        $soldeJour     = $recettesJour - $depensesJour;
        $impayes       = Facture::where('statut', 'en_attente')->sum('montant');

        // Paiements
        $queryP = Paiement::with('patient');
        if ($request->filled('search')) {
            $s = $request->search;
            $queryP->whereHas('patient', fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%"));
        }
        if ($request->filled('statut_p')) {
            $queryP->where('statut', $request->statut_p);
        }
        $paiements = $queryP->orderBy('date_paiement', 'desc')->paginate(15, ['*'], 'page_p');

        // Transactions
        $queryT = Transaction::query();
        if ($request->filled('type_t')) {
            $queryT->where('type', $request->type_t);
        }
        $transactions = $queryT->orderBy('date', 'desc')->paginate(15, ['*'], 'page_t');

        $patients = Patient::orderBy('nom')->orderBy('prenom')->get();

        $stats = [
            'recettes_jour' => $recettesJour,
            'depenses_jour' => $depensesJour,
            'solde_jour'    => $soldeJour,
            'impayes'       => $impayes,
        ];

        return view('admin.caisse', compact('stats', 'paiements', 'transactions', 'patients'));
    }

    public function storePaiement(Request $request)
    {
        $validated = $request->validate([
            'patient_id'    => 'required|exists:patients,id',
            'type'          => 'required|string',
            'montant'       => 'required|integer|min:1',
            'mode_paiement' => 'required|string',
            'date_paiement' => 'required|date',
            'description'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            Paiement::create(array_merge($validated, ['statut' => 'paye']));

            Transaction::create([
                'date'        => $validated['date_paiement'],
                'type'        => 'entree',
                'montant'     => $validated['montant'],
                'description' => $validated['description'] ?? $validated['type'] . ' - Patient #' . $validated['patient_id'],
                'categorie'   => strtolower($validated['type']),
            ]);
        });

        return redirect()->back()->with('success', 'Paiement enregistré avec succès');
    }

    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'type'        => 'required|in:entree,sortie',
            'montant'     => 'required|integer|min:1',
            'description' => 'required|string|max:255',
            'categorie'   => 'nullable|string|max:100',
        ]);

        Transaction::create(array_merge($validated, [
            'date'      => today(),
            'categorie' => $validated['categorie'] ?? 'autre',
        ]));

        return redirect()->back()->with('success', 'Transaction enregistrée');
    }

    public function sortieHospitalisation(Hospitalisation $hospitalisation)
    {
        DB::transaction(function () use ($hospitalisation) {
            $hospitalisation->update([
                'statut'      => 'termine',
                'date_sortie' => today(),
            ]);

            Chambre::find($hospitalisation->chambre_id)->update([
                'statut'     => 'libre',
                'patient_id' => null,
            ]);
        });

        return redirect()->back()->with('success', 'Sortie enregistrée');
    }
}
