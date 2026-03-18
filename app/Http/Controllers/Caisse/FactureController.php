<?php

namespace App\Http\Controllers\Caisse;

use App\Http\Controllers\Controller;
use App\Helpers\AuditHelper;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Models\CaisseSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $query = Facture::with(['patient', 'lignes']);

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date', [$request->date_debut, $request->date_fin]);
        } elseif ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->date_debut);
        } elseif ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->date_fin);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('patient', fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%"));
        }

        $factures = $query->orderBy('date', 'desc')->paginate(20)->appends($request->query());

        $sessionOuverte = \App\Models\CaisseSession::where('user_id', auth()->id())
            ->where('statut', 'ouverte')->first();

        return view('caisse.factures.index', compact('factures', 'sessionOuverte'));
    }

    public function show(Facture $facture)
    {
        $facture->load(['patient', 'lignes', 'consultation.medecin', 'paiements.encaisseur', 'remboursements.effectueur', 'avoirs']);
        $sessionOuverte = \App\Models\CaisseSession::where('user_id', auth()->id())
            ->where('statut', 'ouverte')->first();
        return view('caisse.factures.show', compact('facture', 'sessionOuverte'));
    }

    /**
     * Récupérer les détails d'une facture (API pour modal)
     */
    public function details(Facture $facture)
    {
        $facture->load(['patient', 'lignes', 'consultation.medecin']);

        return response()->json([
            'id' => $facture->id,
            'numero' => $facture->numero,
            'date' => $facture->date->format('d/m/Y'),
            'statut' => $facture->statut,
            'montant_total' => $facture->montant_total,
            'patient' => [
                'id' => $facture->patient->id,
                'nom' => $facture->patient->nom,
                'prenom' => $facture->patient->prenom,
                'telephone' => $facture->patient->telephone,
            ],
            'medecin' => $facture->consultation && $facture->consultation->medecin ? [
                'nom' => $facture->consultation->medecin->nom,
                'prenom' => $facture->consultation->medecin->prenom,
            ] : null,
            'lignes' => $facture->lignes->map(function($ligne) {
                return [
                    'description' => $ligne->description,
                    'quantite' => $ligne->quantite,
                    'prix_unitaire' => $ligne->prix_unitaire,
                    'montant' => $ligne->montant,
                ];
            }),
        ]);
    }

    public function pdf(Facture $facture)
    {
        $facture->load(['patient', 'lignes', 'paiements']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('caisse.facture-pdf', compact('facture'));
        return $pdf->stream('facture-' . $facture->id . '.pdf');
    }

    public function encaisser(Request $request, Facture $facture)
    {
        // Vérifier que la caisse est ouverte
        $sessionOuverte = \App\Models\CaisseSession::where('user_id', auth()->id())
            ->where('statut', 'ouverte')->first();
        if (!$sessionOuverte) {
            return redirect()->back()->with('error', 'Impossible d\'encaisser : la caisse n\'est pas ouverte. Veuillez ouvrir votre session de caisse.');
        }

        $validated = $request->validate([
            'montant' => 'required|integer|min:1',
            'mode_paiement' => 'required|in:especes,carte,mobile_money,cheque,virement',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $montantRestant = ($facture->montant_net ?: $facture->montant) - $facture->montant_paye;

        if ($validated['montant'] > $montantRestant) {
            return redirect()->back()->with('error', 'Le montant saisi dépasse le solde restant (' . number_format($montantRestant, 0, ',', ' ') . ' F).');
        }

        try {
            DB::transaction(function () use ($facture, $validated, $montantRestant) {
                // Generate receipt number
                $numeroRecu = 'REC-' . date('Ymd') . '-' . str_pad(Paiement::whereDate('date_paiement', today())->count() + 1, 4, '0', STR_PAD_LEFT);

                // Create payment record
                $paiement = Paiement::create([
                    'patient_id' => $facture->patient_id,
                    'facture_id' => $facture->id,
                    'date_paiement' => now(),
                    'montant' => $validated['montant'],
                    'type' => 'consultation',
                    'description' => 'Paiement facture ' . $facture->numero,
                    'mode_paiement' => $validated['mode_paiement'],
                    'reference' => $validated['reference'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'numero_recu' => $numeroRecu,
                    'statut' => 'paye',
                    'encaisse_par' => auth()->id(),
                ]);

                // Update facture
                $nouveauMontantPaye = $facture->montant_paye + $validated['montant'];
                $montantNet = $facture->montant_net ?: $facture->montant;
                $nouveauRestant = $montantNet - $nouveauMontantPaye;

                $updateData = [
                    'montant_paye' => $nouveauMontantPaye,
                    'montant_restant' => max(0, $nouveauRestant),
                    'encaisse_par' => auth()->id(),
                ];

                // Full payment
                if ($nouveauRestant <= 0) {
                    $updateData['statut'] = 'payee';
                    $updateData['mode_paiement'] = $validated['mode_paiement'];
                    $updateData['date_paiement'] = now();
                    $updateData['reference_paiement'] = $validated['reference'] ?? null;
                }

                $facture->update($updateData);

                // Create transaction entry
                Transaction::create([
                    'date' => today(),
                    'type' => 'entree',
                    'montant' => $validated['montant'],
                    'description' => 'Encaissement facture ' . $facture->numero . ' - ' . $facture->patient->prenom . ' ' . $facture->patient->nom,
                    'categorie' => 'consultation',
                ]);

                // Update session totals if session is open
                $session = CaisseSession::where('user_id', auth()->id())->where('statut', 'ouverte')->first();
                if ($session) {
                    $session->increment('total_encaissements', $validated['montant']);
                }
            });

            AuditHelper::log('update', 'Encaissement ' . number_format($validated['montant'], 0, ',', ' ') . ' F sur facture ' . $facture->numero, $facture);

            return redirect()->route('caisse.index')->with('success', 'Paiement de ' . number_format($validated['montant'], 0, ',', ' ') . ' F enregistré avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'encaissement : ' . $e->getMessage());
        }
    }

    // Reçu PDF
    public function recu(Paiement $paiement)
    {
        $paiement->load(['patient', 'facture.lignes', 'encaisseur']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('caisse.recu-pdf', compact('paiement'));
        return $pdf->stream('recu-' . $paiement->numero_recu . '.pdf');
    }

    // Avoir / Credit note
    public function storeAvoir(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'montant' => 'required|integer|min:1|max:' . $facture->montant,
            'motif' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $numero = 'AV-' . date('Ymd') . '-' . str_pad(\App\Models\Avoir::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        \App\Models\Avoir::create([
            'numero' => $numero,
            'facture_id' => $facture->id,
            'patient_id' => $facture->patient_id,
            'montant' => $validated['montant'],
            'motif' => $validated['motif'],
            'notes' => $validated['notes'],
            'statut' => 'applique',
            'created_by' => auth()->id(),
        ]);

        // Adjust facture
        $facture->decrement('montant_net', $validated['montant']);
        $facture->decrement('montant_restant', $validated['montant']);

        AuditHelper::log('create', 'Avoir ' . $numero . ' créé: ' . number_format($validated['montant'], 0, ',', ' ') . ' F', $facture);

        return redirect()->back()->with('success', 'Avoir de ' . number_format($validated['montant'], 0, ',', ' ') . ' F créé.');
    }

    // Annuler une facture
    public function annuler(Facture $facture)
    {
        if ($facture->statut === 'payee') {
            return redirect()->back()->with('error', 'Impossible d\'annuler une facture déjà payée.');
        }

        $facture->update(['statut' => 'annulee']);
        AuditHelper::log('update', 'Facture ' . $facture->numero . ' annulée', $facture);

        return redirect()->back()->with('success', 'Facture annulée.');
    }

    // Patient outstanding balance
    public function soldePatient($patientId)
    {
        $factures = Facture::where('patient_id', $patientId)
            ->whereIn('statut', ['en_attente', 'envoyee'])
            ->with('lignes')->get();

        $totalDu = $factures->sum(fn($f) => ($f->montant_net ?: $f->montant) - $f->montant_paye);

        return response()->json([
            'patient_id' => $patientId,
            'factures_impayees' => $factures->count(),
            'total_du' => $totalDu,
            'factures' => $factures->map(fn($f) => [
                'id' => $f->id,
                'numero' => $f->numero,
                'montant' => $f->montant,
                'paye' => $f->montant_paye,
                'restant' => ($f->montant_net ?: $f->montant) - $f->montant_paye,
                'date' => $f->date,
            ]),
        ]);
    }

    public function releve(Request $request, $patientId)
    {
        $patient = \App\Models\Patient::findOrFail($patientId);

        $factures = Facture::where('patient_id', $patientId)
            ->with('lignes')
            ->orderBy('date', 'desc')
            ->get();

        $paiements = Paiement::where('patient_id', $patientId)
            ->with('facture')
            ->orderBy('date_paiement', 'desc')
            ->get();

        $totaux = [
            'total_facture' => $factures->sum('montant'),
            'total_paye' => $paiements->where('statut', 'paye')->sum('montant'),
            'solde_du' => $factures->whereIn('statut', ['en_attente', 'envoyee'])->sum(fn($f) => ($f->montant_net ?: $f->montant) - $f->montant_paye),
        ];

        if ($request->get('format') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('caisse.releve-pdf', compact('patient', 'factures', 'paiements', 'totaux'));
            return $pdf->stream('releve-' . $patient->nom . '-' . $patient->prenom . '.pdf');
        }

        return view('caisse.releve', compact('patient', 'factures', 'paiements', 'totaux'));
    }

    public function storeRemboursement(Request $request, Facture $facture, Paiement $paiement)
    {
        // Vérifier que la caisse est ouverte
        if (!\App\Models\CaisseSession::where('user_id', auth()->id())->where('statut', 'ouverte')->exists()) {
            return redirect()->back()->with('error', 'Impossible de rembourser : la caisse n\'est pas ouverte.');
        }

        $validated = $request->validate([
            'montant' => 'required|integer|min:1|max:' . $paiement->montant,
            'motif' => 'required|string|max:255',
            'mode_remboursement' => 'required|in:especes,carte,virement',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($facture, $paiement, $validated) {
                $numero = 'RMB-' . date('Ymd') . '-' . str_pad(\App\Models\Remboursement::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

                \App\Models\Remboursement::create([
                    'numero' => $numero,
                    'paiement_id' => $paiement->id,
                    'facture_id' => $facture->id,
                    'patient_id' => $facture->patient_id,
                    'montant' => $validated['montant'],
                    'motif' => $validated['motif'],
                    'mode_remboursement' => $validated['mode_remboursement'],
                    'notes' => $validated['notes'],
                    'statut' => 'effectue',
                    'effectue_par' => auth()->id(),
                ]);

                // Update facture
                $facture->decrement('montant_paye', $validated['montant']);
                $facture->increment('montant_restant', $validated['montant']);
                if ($facture->montant_paye <= 0) {
                    $facture->update(['statut' => 'en_attente', 'date_paiement' => null]);
                } elseif ($facture->montant_restant > 0) {
                    $facture->update(['statut' => 'en_attente']);
                }

                // Record transaction
                Transaction::create([
                    'date' => today(),
                    'type' => 'sortie',
                    'montant' => $validated['montant'],
                    'description' => 'Remboursement ' . $numero . ' - ' . $facture->patient->prenom . ' ' . $facture->patient->nom,
                    'categorie' => 'remboursement',
                ]);

                AuditHelper::log('create', 'Remboursement ' . $numero . ' de ' . number_format($validated['montant'], 0, ',', ' ') . ' F', $facture);
            });

            return redirect()->back()->with('success', 'Remboursement effectue avec succes.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function appliquerPriseEnCharge(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'type_prise_en_charge' => 'required|in:assurance,mutuelle,indigent',
            'organisme_prise_en_charge' => 'nullable|string|max:255',
            'numero_assurance' => 'nullable|string|max:100',
            'taux_couverture' => 'required|integer|min:1|max:100',
        ]);

        $montantBase = $facture->montant_net ?: $facture->montant;
        $montantCouvert = (int)round($montantBase * $validated['taux_couverture'] / 100);
        $montantPatient = $montantBase - $montantCouvert;

        $facture->update(array_merge($validated, [
            'montant_couvert' => $montantCouvert,
            'montant_patient' => $montantPatient,
            'montant_restant' => max(0, $montantPatient - $facture->montant_paye),
        ]));

        AuditHelper::log('update', 'Prise en charge appliquee: ' . $validated['type_prise_en_charge'] . ' ' . $validated['taux_couverture'] . '%', $facture);

        return redirect()->back()->with('success', 'Prise en charge appliquee. Part patient: ' . number_format($montantPatient, 0, ',', ' ') . ' F');
    }

    public function destroy(Facture $facture)
    {
        if ($facture->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les factures en attente peuvent être supprimées.');
        }

        // Supprimer les lignes de facture
        $facture->lignes()->delete();

        $facture->delete();

        return redirect()->back()->with('success', 'Facture supprimée avec succès.');
    }
}
