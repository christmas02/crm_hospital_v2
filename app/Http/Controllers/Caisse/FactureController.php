<?php

namespace App\Http\Controllers\Caisse;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    public function index()
    {
        $factures = Facture::with(['patient', 'lignes'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('caisse.factures.index', compact('factures'));
    }

    public function show(Facture $facture)
    {
        $facture->load(['patient', 'lignes', 'consultation.medecin']);
        return view('caisse.factures.show', compact('facture'));
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

    public function encaisser(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'mode_paiement' => 'nullable|in:especes,carte,mobile_money,cheque',
        ]);

        $modePaiement = $validated['mode_paiement'] ?? 'especes';

        DB::transaction(function () use ($modePaiement, $facture) {
            $facture->update([
                'statut' => 'payee',
                'mode_paiement' => $modePaiement,
                'date_paiement' => now(),
            ]);

            // Créer le paiement
            Paiement::create([
                'patient_id' => $facture->patient_id,
                'facture_id' => $facture->id,
                'date_paiement' => now(),
                'montant' => $facture->montant_total,
                'type' => 'consultation',
                'description' => 'Paiement facture ' . $facture->numero,
                'mode_paiement' => $modePaiement,
                'statut' => 'paye',
            ]);

            // Créer la transaction
            Transaction::create([
                'date' => now(),
                'type' => 'entree',
                'montant' => $facture->montant_total,
                'description' => 'Encaissement facture ' . $facture->numero,
                'categorie' => 'consultation',
            ]);
        });

        return redirect()->route('caisse.index')
            ->with('success', 'Facture encaissée avec succès');
    }
}
