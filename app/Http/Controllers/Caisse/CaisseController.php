<?php

namespace App\Http\Controllers\Caisse;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Models\CaisseSession;
use App\Helpers\AuditHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaisseController extends Controller
{
    public function index()
    {
        // Check if there's an open session
        $sessionOuverte = CaisseSession::where('user_id', auth()->id())
            ->where('statut', 'ouverte')->first();

        // Factures en attente
        $facturesEnAttente = Facture::with(['patient', 'lignes'])
            ->whereIn('statut', ['en_attente', 'envoyee'])
            ->orderBy('date', 'desc')->get();

        // Encaissements du jour
        $encaissementsJour = Paiement::with('patient')
            ->whereDate('date_paiement', today())
            ->orderBy('date_paiement', 'desc')->limit(15)->get();

        // Stats
        $stats = [
            'en_attente_count' => Facture::whereIn('statut', ['en_attente', 'envoyee'])->count(),
            'en_attente_montant' => Facture::whereIn('statut', ['en_attente', 'envoyee'])->sum('montant'),
            'encaisse_jour' => Paiement::whereDate('date_paiement', today())->where('statut', 'paye')->sum('montant'),
            'depenses_jour' => Transaction::whereDate('date', today())->where('type', 'sortie')->sum('montant'),
            'partielles' => Facture::where('montant_paye', '>', 0)->where('statut', '!=', 'payee')->count(),
        ];

        // Chart data
        $financeDays = collect(range(6, 0))->map(fn($i) => now()->subDays($i));
        $financeParJour = $financeDays->map(fn($d) => [
            'date' => $d->locale('fr')->isoFormat('ddd D'),
            'recettes' => Transaction::where('type', 'entree')->whereDate('date', $d->toDateString())->sum('montant'),
            'depenses' => Transaction::where('type', 'sortie')->whereDate('date', $d->toDateString())->sum('montant'),
        ]);

        // Ventilation par mode
        $ventilationMode = Paiement::whereDate('date_paiement', today())
            ->where('statut', 'paye')
            ->selectRaw('mode_paiement, count(*) as nb, sum(montant) as total')
            ->groupBy('mode_paiement')
            ->get();

        // Dernier solde de fermeture pour pré-remplir l'ouverture
        $dernierSoldeFermeture = CaisseSession::where('statut', 'fermee')
            ->orderBy('fermeture', 'desc')
            ->value('solde_fermeture') ?? 0;

        $useCharts = true;

        return view('caisse.index', compact('sessionOuverte', 'facturesEnAttente', 'encaissementsJour', 'stats', 'financeParJour', 'ventilationMode', 'dernierSoldeFermeture', 'useCharts'));
    }

    // Session caisse (ouverture/fermeture)
    public function ouvrirSession(Request $request)
    {
        $request->validate([
            'solde_ouverture' => 'required|integer|min:0',
            'notes_ouverture' => 'nullable|string',
        ]);

        // Vérifier qu'il n'y a pas déjà une session ouverte
        if (CaisseSession::where('user_id', auth()->id())->where('statut', 'ouverte')->exists()) {
            return redirect()->back()->with('error', 'Vous avez déjà une session de caisse ouverte.');
        }

        CaisseSession::create([
            'user_id' => auth()->id(),
            'ouverture' => now(),
            'solde_ouverture' => $request->solde_ouverture,
            'notes_ouverture' => $request->notes_ouverture,
            'statut' => 'ouverte',
        ]);

        AuditHelper::log('create', 'Session de caisse ouverte - Solde: ' . number_format($request->solde_ouverture, 0, ',', ' ') . ' F');

        return redirect()->back()->with('success', 'Session de caisse ouverte avec succès');
    }

    public function fermerSession(Request $request)
    {
        $session = CaisseSession::where('user_id', auth()->id())
            ->where('statut', 'ouverte')->firstOrFail();

        $request->validate([
            'solde_fermeture' => 'required|integer|min:0',
            'notes_fermeture' => 'nullable|string',
        ]);

        // Calculer les totaux de la session
        $totalEncaissements = Paiement::where('statut', 'paye')
            ->whereBetween('date_paiement', [$session->ouverture, now()])->sum('montant');
        $totalDepenses = Transaction::where('type', 'sortie')
            ->whereBetween('created_at', [$session->ouverture, now()])->sum('montant');

        $session->update([
            'fermeture' => now(),
            'solde_fermeture' => $request->solde_fermeture,
            'total_encaissements' => $totalEncaissements,
            'total_depenses' => $totalDepenses,
            'notes_fermeture' => $request->notes_fermeture,
            'statut' => 'fermee',
        ]);

        AuditHelper::log('update', 'Session de caisse fermée - Solde: ' . number_format($request->solde_fermeture, 0, ',', ' ') . ' F');

        return redirect()->back()->with('success', 'Session de caisse fermée. Écart: ' . number_format($request->solde_fermeture - ($session->solde_ouverture + $totalEncaissements - $totalDepenses), 0, ',', ' ') . ' F');
    }

    public function sessionsHistorique()
    {
        $sessions = CaisseSession::with('user')
            ->orderBy('ouverture', 'desc')
            ->paginate(20);

        return view('caisse.sessions', compact('sessions'));
    }

    public function historique(Request $request)
    {
        $query = Paiement::with(['patient', 'facture', 'encaisseur']);

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_paiement', [$request->date_debut, $request->date_fin . ' 23:59:59']);
        } elseif ($request->filled('date')) {
            $query->whereDate('date_paiement', $request->date);
        }

        if ($request->filled('mode_paiement')) {
            $query->where('mode_paiement', $request->mode_paiement);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('patient', fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%"));
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(20);

        $totaux = [
            'total' => (clone $query)->sum('montant'),
            'count' => (clone $query)->count(),
        ];

        return view('caisse.historique', compact('paiements', 'totaux'));
    }

    public function journal(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date', [$request->date_debut, $request->date_fin]);
        } elseif ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $transactions = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(30);

        // Calculer les totaux sur le même filtre
        $totauxQuery = Transaction::query();
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $totauxQuery->whereBetween('date', [$request->date_debut, $request->date_fin]);
        } elseif ($request->filled('date')) {
            $totauxQuery->whereDate('date', $request->date);
        }
        if ($request->filled('type')) {
            $totauxQuery->where('type', $request->type);
        }
        if ($request->filled('categorie')) {
            $totauxQuery->where('categorie', $request->categorie);
        }

        $totaux = [
            'entrees' => (clone $totauxQuery)->where('type', 'entree')->sum('montant'),
            'sorties' => (clone $totauxQuery)->where('type', 'sortie')->sum('montant'),
        ];
        $totaux['solde'] = $totaux['entrees'] - $totaux['sorties'];

        return view('caisse.journal', compact('transactions', 'totaux'));
    }

    public function journalPdf(Request $request)
    {
        $query = Transaction::query();

        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', now()->format('Y-m-d'));

        $query->whereBetween('date', [$dateDebut, $dateFin]);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->orderBy('date')->orderBy('created_at')->get();

        $totaux = [
            'entrees' => $transactions->where('type', 'entree')->sum('montant'),
            'sorties' => $transactions->where('type', 'sortie')->sum('montant'),
        ];
        $totaux['solde'] = $totaux['entrees'] - $totaux['sorties'];

        $periode = \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') . ' au ' . \Carbon\Carbon::parse($dateFin)->format('d/m/Y');
        $typeFiltre = $request->get('type', 'tous');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('caisse.journal-pdf', compact('transactions', 'totaux', 'periode', 'typeFiltre'));
        return $pdf->stream('journal-caisse-' . $dateDebut . '-' . $dateFin . '.pdf');
    }

    public function storeDepense(Request $request)
    {
        // Vérifier que la caisse est ouverte
        if (!CaisseSession::where('user_id', auth()->id())->where('statut', 'ouverte')->exists()) {
            return redirect()->back()->with('error', 'Impossible d\'enregistrer une dépense : la caisse n\'est pas ouverte.');
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'montant' => 'required|integer|min:1',
            'categorie' => 'required|string|max:100',
        ]);

        Transaction::create(array_merge($validated, [
            'date' => today(),
            'type' => 'sortie',
        ]));

        AuditHelper::log('create', 'Dépense enregistrée: ' . $validated['description'] . ' - ' . number_format($validated['montant'], 0, ',', ' ') . ' F');

        return redirect()->back()->with('success', 'Dépense enregistrée');
    }

    public function rapportJournalier(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $dateCarbon = \Carbon\Carbon::parse($date);

        // Encaissements du jour
        $paiements = Paiement::with(['patient', 'facture', 'encaisseur'])
            ->whereDate('date_paiement', $date)
            ->where('statut', 'paye')
            ->orderBy('date_paiement')
            ->get();

        // Ventilation par mode de paiement
        $parMode = $paiements->groupBy('mode_paiement')->map(fn($group) => [
            'count' => $group->count(),
            'total' => $group->sum('montant'),
        ]);

        // Dépenses du jour
        $depenses = Transaction::where('type', 'sortie')
            ->whereDate('date', $date)
            ->orderBy('created_at')
            ->get();

        // Session de caisse du jour
        $session = CaisseSession::whereDate('ouverture', $date)->first();

        $totaux = [
            'encaissements' => $paiements->sum('montant'),
            'depenses' => $depenses->sum('montant'),
            'nb_paiements' => $paiements->count(),
            'nb_depenses' => $depenses->count(),
        ];
        $totaux['solde'] = $totaux['encaissements'] - $totaux['depenses'];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('caisse.rapport-journalier-pdf', compact(
            'paiements', 'parMode', 'depenses', 'session', 'totaux', 'dateCarbon'
        ));
        return $pdf->stream('rapport-journalier-' . $date . '.pdf');
    }

    public function creances()
    {
        $today = now();

        $factures = Facture::with('patient')
            ->whereIn('statut', ['en_attente', 'envoyee'])
            ->orderBy('date')
            ->get()
            ->map(function($f) use ($today) {
                $jours = $today->diffInDays(\Carbon\Carbon::parse($f->date));
                $restant = ($f->montant_net ?: $f->montant) - $f->montant_paye;
                $f->jours_retard = $jours;
                $f->montant_restant_calc = $restant;
                $f->tranche = match(true) {
                    $jours <= 30 => '0-30',
                    $jours <= 60 => '31-60',
                    $jours <= 90 => '61-90',
                    default => '90+',
                };
                return $f;
            });

        $parTranche = [
            '0-30' => $factures->where('tranche', '0-30'),
            '31-60' => $factures->where('tranche', '31-60'),
            '61-90' => $factures->where('tranche', '61-90'),
            '90+' => $factures->where('tranche', '90+'),
        ];

        $totaux = [
            'total' => $factures->sum('montant_restant_calc'),
            '0-30' => $parTranche['0-30']->sum('montant_restant_calc'),
            '31-60' => $parTranche['31-60']->sum('montant_restant_calc'),
            '61-90' => $parTranche['61-90']->sum('montant_restant_calc'),
            '90+' => $parTranche['90+']->sum('montant_restant_calc'),
            'count' => $factures->count(),
        ];

        $useCharts = true;

        return view('caisse.creances', compact('factures', 'parTranche', 'totaux', 'useCharts'));
    }

    public function priseEnCharge(Request $request)
    {
        // Toutes les factures avec prise en charge non entièrement payées
        $query = Facture::with('patient')
            ->whereNotNull('type_prise_en_charge')
            ->where('montant_couvert', '>', 0);

        $organismeFiltre = $request->get('organisme');
        if ($organismeFiltre) {
            $query->where('organisme_prise_en_charge', $organismeFiltre);
        }

        $factures = $query->orderBy('organisme_prise_en_charge')->orderBy('date', 'desc')->get();

        // Regrouper par organisme
        $parOrganisme = $factures->groupBy('organisme_prise_en_charge')->map(function ($group, $organisme) {
            return [
                'organisme' => $organisme ?: 'Non spécifié',
                'type' => $group->first()->type_prise_en_charge,
                'nb_factures' => $group->count(),
                'total_couvert' => $group->sum('montant_couvert'),
                'total_facture' => $group->sum('montant'),
                'factures' => $group,
            ];
        });

        $totaux = [
            'nb_organismes' => $parOrganisme->count(),
            'nb_factures' => $factures->count(),
            'total_couvert' => $factures->sum('montant_couvert'),
            'total_factures' => $factures->sum('montant'),
        ];

        $organismes = Facture::whereNotNull('organisme_prise_en_charge')
            ->distinct()->pluck('organisme_prise_en_charge');

        return view('caisse.prise-en-charge', compact('parOrganisme', 'totaux', 'organismes', 'organismeFiltre'));
    }

    public static function getFacturesEnAttenteCount()
    {
        return Facture::whereIn('statut', ['en_attente', 'envoyee'])->count();
    }
}
