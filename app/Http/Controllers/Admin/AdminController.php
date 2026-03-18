<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Chambre;
use App\Models\Consultation;
use App\Models\Hospitalisation;
use App\Models\Patient;
use App\Models\Planning;
use App\Models\Rendezvous;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Models\Facture;
use App\Helpers\AuditHelper;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $usersWithoutMedecin = User::where('role', 'medecin')->whereDoesntHave('medecin')->get();

        return view('admin.medecins', compact('medecins', 'specialites', 'usersWithoutMedecin'));
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
            'photo'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'user_id'            => 'nullable|exists:users,id',
        ]);

        $data = array_merge($validated, ['statut' => 'disponible']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('medecins', 'public');
        }

        Medecin::create($data);

        AuditHelper::log('create', 'Médecin ajouté: Dr. ' . $validated['prenom'] . ' ' . $validated['nom']);

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

    public function showMedecinJson(Medecin $medecin)
    {
        $medecin->loadCount(['consultations', 'hospitalisations']);

        return response()->json([
            'id' => $medecin->id,
            'nom' => $medecin->nom,
            'prenom' => $medecin->prenom,
            'specialite' => $medecin->specialite,
            'telephone' => $medecin->telephone ?? '',
            'email' => $medecin->email ?? '',
            'bureau' => $medecin->bureau ?? '',
            'statut' => $medecin->statut,
            'tarif_consultation' => $medecin->tarif_consultation ?? 0,
            'photo' => $medecin->photo ? asset('storage/' . $medecin->photo) : null,
            'initiales' => strtoupper(substr($medecin->prenom, 0, 1) . substr($medecin->nom, 0, 1)),
            'consultations_count' => $medecin->consultations_count,
            'hospitalisations_count' => $medecin->hospitalisations_count,
            'tarif_format' => $medecin->tarif_consultation ? number_format($medecin->tarif_consultation, 0, ',', ' ') . ' F' : '—',
        ]);
    }

    public function updateMedecinFull(Request $request, Medecin $medecin)
    {
        $validated = $request->validate([
            'nom'                => 'required|string|max:100',
            'prenom'             => 'required|string|max:100',
            'specialite'         => 'required|string|max:100',
            'telephone'          => 'required|string|max:20',
            'email'              => 'nullable|email|unique:medecins,email,' . $medecin->id,
            'bureau'             => 'nullable|string|max:50',
            'tarif_consultation' => 'nullable|integer|min:0',
            'statut'             => 'required|in:disponible,en_consultation,absent',
            'photo'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($medecin->photo) {
                Storage::disk('public')->delete($medecin->photo);
            }
            $validated['photo'] = $request->file('photo')->store('medecins', 'public');
        }

        $medecin->update($validated);

        return redirect()->route('admin.medecins')->with('success', 'Médecin mis à jour avec succès');
    }

    public function updateMedecinPhoto(Request $request, Medecin $medecin)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Supprimer l'ancienne photo
        if ($medecin->photo) {
            Storage::disk('public')->delete($medecin->photo);
        }

        $medecin->update([
            'photo' => $request->file('photo')->store('medecins', 'public'),
        ]);

        return redirect()->back()->with('success', 'Photo mise à jour');
    }

    public function destroyMedecin(Medecin $medecin)
    {
        // Vérifier s'il a des consultations actives (en_attente ou en_cours)
        $activeConsultations = Consultation::where('medecin_id', $medecin->id)
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->exists();

        if ($activeConsultations) {
            return redirect()->back()->with('error', 'Impossible de supprimer ce médecin : il a des consultations actives.');
        }

        // Supprimer la photo si elle existe
        if ($medecin->photo) {
            Storage::disk('public')->delete($medecin->photo);
        }

        AuditHelper::log('delete', 'Médecin supprimé: Dr. ' . $medecin->prenom . ' ' . $medecin->nom, $medecin);

        $medecin->delete();

        return redirect()->back()->with('success', 'Médecin supprimé avec succès.');
    }

    // ==================== CHAMBRES ====================

    public function storeChambre(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:20|unique:chambres,numero',
            'etage' => 'required|integer|min:0',
            'type' => 'required|in:individuelle,double,vip,suite',
            'capacite' => 'required|integer|min:1|max:10',
            'tarif_jour' => 'required|integer|min:0',
            'equipements' => 'nullable|string',
        ]);

        Chambre::create(array_merge($validated, ['statut' => 'libre']));

        \App\Helpers\AuditHelper::log('create', 'Chambre N° ' . $validated['numero'] . ' créée (Étage ' . $validated['etage'] . ')');

        return redirect()->back()->with('success', 'Chambre créée avec succès');
    }

    public function updateChambre(Request $request, Chambre $chambre)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:20|unique:chambres,numero,' . $chambre->id,
            'etage' => 'required|integer|min:0',
            'type' => 'required|in:individuelle,double,vip,suite',
            'capacite' => 'required|integer|min:1|max:10',
            'tarif_jour' => 'required|integer|min:0',
            'statut' => 'required|in:libre,occupee,maintenance',
            'equipements' => 'nullable|string',
        ]);

        // Don't allow status change to 'libre' if patient is assigned
        if ($chambre->patient_id && $validated['statut'] === 'libre') {
            return redirect()->back()->with('error', 'Impossible : un patient est encore assigné à cette chambre.');
        }

        $chambre->update($validated);

        return redirect()->back()->with('success', 'Chambre mise à jour');
    }

    public function destroyChambre(Chambre $chambre)
    {
        if ($chambre->statut === 'occupee') {
            return redirect()->back()->with('error', 'Impossible de supprimer une chambre occupée.');
        }

        if ($chambre->hospitalisations()->where('statut', 'en_cours')->exists()) {
            return redirect()->back()->with('error', 'Impossible : une hospitalisation est en cours dans cette chambre.');
        }

        $chambre->delete();

        \App\Helpers\AuditHelper::log('delete', 'Chambre N° ' . $chambre->numero . ' supprimée');

        return redirect()->back()->with('success', 'Chambre supprimée');
    }

    public function chambreJson(Chambre $chambre)
    {
        return response()->json([
            'id' => $chambre->id,
            'numero' => $chambre->numero,
            'etage' => $chambre->etage,
            'type' => $chambre->type,
            'capacite' => $chambre->capacite,
            'tarif_jour' => $chambre->tarif_jour,
            'statut' => $chambre->statut,
            'equipements' => $chambre->equipements ?? '',
        ]);
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

        $patients = Patient::orderBy('nom')->get();

        return view('admin.planning', compact('medecins', 'rendezvous', 'planningMedecin', 'patients'));
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

        try {
            DB::transaction(function () use ($validated) {
                Hospitalisation::create(array_merge($validated, [
                    'date_admission' => today(),
                    'statut'         => 'en_cours',
                ]));

                Chambre::find($validated['chambre_id'])->update([
                    'statut'     => 'occupee',
                    'patient_id' => $validated['patient_id'],
                ]);

                Patient::find($validated['patient_id'])->update(['statut' => 'hospitalise']);
            });

            AuditHelper::log('create', 'Admission patient #' . $validated['patient_id'] . ' en chambre #' . $validated['chambre_id']);

            return redirect()->back()->with('success', 'Patient admis avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
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
        $paiements = $queryP->orderBy('date_paiement', 'desc')->paginate(15, ['*'], 'page_p')->appends($request->query());

        // Transactions
        $queryT = Transaction::query();
        if ($request->filled('type_t')) {
            $queryT->where('type', $request->type_t);
        }
        $transactions = $queryT->orderBy('date', 'desc')->paginate(15, ['*'], 'page_t')->appends($request->query());

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
        try {
            DB::transaction(function () use ($hospitalisation) {
                $hospitalisation->update([
                    'statut'      => 'termine',
                    'date_sortie' => today(),
                ]);

                Chambre::find($hospitalisation->chambre_id)->update([
                    'statut'     => 'libre',
                    'patient_id' => null,
                ]);

                // Update patient status back to actif
                $hospitalisation->patient->update(['statut' => 'actif']);

                // Create facture for hospitalisation charges
                $jours = \Carbon\Carbon::parse($hospitalisation->date_admission)->diffInDays(today()) ?: 1;
                $chambre = Chambre::find($hospitalisation->chambre_id);
                $tarifTotal = $jours * ($chambre->tarif_jour ?? 0);

                if ($tarifTotal > 0) {
                    $numero = 'FAC-' . date('Y') . '-' . str_pad(Facture::whereYear('created_at', date('Y'))->count() + 1, 5, '0', STR_PAD_LEFT);
                    $facture = Facture::create([
                        'numero' => $numero,
                        'patient_id' => $hospitalisation->patient_id,
                        'date' => today(),
                        'montant' => $tarifTotal,
                        'montant_net' => $tarifTotal,
                        'montant_restant' => $tarifTotal,
                        'statut' => 'en_attente',
                    ]);

                    \App\Models\FactureLigne::create([
                        'facture_id' => $facture->id,
                        'description' => 'Hospitalisation ' . $jours . ' jour(s) - Chambre ' . $chambre->numero,
                        'quantite' => $jours,
                        'prix_unitaire' => $chambre->tarif_jour ?? 0,
                        'total' => $tarifTotal,
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Sortie enregistrée');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    // ==================== ANALYTICS ====================

    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays((int)$period);

        // Consultations par jour
        $consultationsParJour = collect(range((int)$period - 1, 0))->map(fn($i) => [
            'date' => now()->subDays($i)->format('d/m'),
            'count' => \App\Models\Consultation::whereDate('date', now()->subDays($i)->toDateString())->count(),
        ]);

        // Recettes par jour
        $recettesParJour = collect(range((int)$period - 1, 0))->map(fn($i) => [
            'date' => now()->subDays($i)->format('d/m'),
            'montant' => \App\Models\Transaction::where('type', 'entree')->whereDate('date', now()->subDays($i)->toDateString())->sum('montant'),
        ]);

        // Top médecins par consultations
        $topMedecins = \App\Models\Medecin::withCount(['consultations' => fn($q) => $q->where('date', '>=', $startDate)])
            ->orderByDesc('consultations_count')->limit(5)->get();

        // Répartition par spécialité
        $parSpecialite = \App\Models\Medecin::select('specialite')
            ->selectRaw('count(*) as total')
            ->groupBy('specialite')->pluck('total', 'specialite');

        // Patients par mois (12 derniers mois)
        $patientsMensuels = collect(range(11, 0))->map(fn($i) => [
            'mois' => now()->subMonths($i)->locale('fr')->isoFormat('MMM YY'),
            'count' => \App\Models\Patient::whereYear('date_inscription', now()->subMonths($i)->year)
                ->whereMonth('date_inscription', now()->subMonths($i)->month)->count(),
        ]);

        // KPIs
        $kpis = [
            'patients_total' => \App\Models\Patient::count(),
            'patients_mois' => \App\Models\Patient::whereMonth('date_inscription', now()->month)->whereYear('date_inscription', now()->year)->count(),
            'consultations_total' => \App\Models\Consultation::where('date', '>=', $startDate)->count(),
            'consultations_terminees' => \App\Models\Consultation::where('date', '>=', $startDate)->where('statut', 'termine')->count(),
            'recettes_periode' => \App\Models\Transaction::where('type', 'entree')->where('date', '>=', $startDate)->sum('montant'),
            'depenses_periode' => \App\Models\Transaction::where('type', 'sortie')->where('date', '>=', $startDate)->sum('montant'),
            'taux_occupation' => \App\Models\Chambre::count() > 0 ? round((\App\Models\Chambre::where('statut', 'occupee')->count() / \App\Models\Chambre::count()) * 100) : 0,
            'medicaments_alerte' => \App\Models\Medicament::whereColumn('stock', '<=', 'stock_min')->count(),
        ];

        $useCharts = true;

        return view('admin.analytics', compact('consultationsParJour', 'recettesParJour', 'topMedecins', 'parSpecialite', 'patientsMensuels', 'kpis', 'period', 'useCharts'));
    }

    // ==================== RAPPORT MENSUEL ====================

    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $data = [
            'mois' => $startDate->locale('fr')->isoFormat('MMMM YYYY'),
            'patients_nouveaux' => \App\Models\Patient::whereBetween('date_inscription', [$startDate, $endDate])->count(),
            'patients_total' => \App\Models\Patient::where('date_inscription', '<=', $endDate)->count(),
            'consultations' => \App\Models\Consultation::whereBetween('date', [$startDate, $endDate])->count(),
            'consultations_terminees' => \App\Models\Consultation::whereBetween('date', [$startDate, $endDate])->where('statut', 'termine')->count(),
            'recettes' => \App\Models\Transaction::where('type', 'entree')->whereBetween('date', [$startDate, $endDate])->sum('montant'),
            'depenses' => \App\Models\Transaction::where('type', 'sortie')->whereBetween('date', [$startDate, $endDate])->sum('montant'),
            'hospitalisations' => \App\Models\Hospitalisation::whereBetween('date_admission', [$startDate, $endDate])->count(),
            'ordonnances' => \App\Models\Ordonnance::whereBetween('created_at', [$startDate, $endDate])->count(),
            'top_medecins' => \App\Models\Medecin::withCount(['consultations' => fn($q) => $q->whereBetween('date', [$startDate, $endDate])])->orderByDesc('consultations_count')->limit(5)->get(),
            'top_medicaments' => \App\Models\Medicament::withCount(['mouvements' => fn($q) => $q->where('type', 'sortie')->whereBetween('date', [$startDate, $endDate])])->orderByDesc('mouvements_count')->limit(5)->get(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.rapport-mensuel-pdf', $data);
        return $pdf->stream('rapport-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf');
    }

    // ==================== RAPPELS RDV ====================

    public function rappels(Request $request)
    {
        $demain = now()->addDay()->format('Y-m-d');

        $consultationsDemain = Consultation::with(['patient', 'medecin'])
            ->whereDate('date', $demain)
            ->where('statut', 'en_attente')
            ->orderBy('heure')
            ->get();

        $consultationsAujourdhui = Consultation::with(['patient', 'medecin'])
            ->whereDate('date', today())
            ->where('statut', 'en_attente')
            ->orderBy('heure')
            ->get();

        return view('admin.rappels', compact('consultationsDemain', 'consultationsAujourdhui'));
    }

    public function envoyerTousRappels(Request $request)
    {
        $date = $request->get('date', now()->addDay()->format('Y-m-d'));

        $consultations = Consultation::with(['patient', 'medecin'])
            ->whereDate('date', $date)
            ->where('statut', 'en_attente')
            ->get();

        $envoyes = 0;
        foreach ($consultations as $consultation) {
            if ($consultation->patient->email) {
                try {
                    $consultation->patient->notify(new \App\Notifications\RappelRendezvous($consultation));
                    $envoyes++;
                } catch (\Exception $e) {}
            }
        }

        return redirect()->back()->with('success', $envoyes . ' rappels envoyes sur ' . $consultations->count() . ' rendez-vous');
    }

    // ==================== AUDIT LOG ====================

    public function auditLog(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(30);
        return view('admin.audit-log', compact('logs'));
    }
}
