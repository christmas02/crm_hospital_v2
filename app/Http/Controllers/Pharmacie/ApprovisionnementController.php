<?php

namespace App\Http\Controllers\Pharmacie;

use App\Http\Controllers\Controller;
use App\Models\FicheApprovisionnement;
use App\Models\ApprovisionnementLigne;
use App\Models\Medicament;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovisionnementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fournisseur' => 'required|string|max:255',
            'date' => 'required|date',
            'observations' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.medicament_id' => 'required|exists:medicaments,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|integer|min:0',
        ]);

        $fiche = DB::transaction(function () use ($validated) {
            // Generate numero
            $count = FicheApprovisionnement::count();
            $numero = 'APP-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            $totalArticles = count($validated['lignes']);
            $totalQuantite = array_sum(array_column($validated['lignes'], 'quantite'));
            $montantTotal = 0;
            foreach ($validated['lignes'] as $ligne) {
                $montantTotal += $ligne['quantite'] * $ligne['prix_unitaire'];
            }

            $fiche = FicheApprovisionnement::create([
                'numero' => $numero,
                'fournisseur' => $validated['fournisseur'],
                'date' => $validated['date'],
                'observations' => $validated['observations'] ?? null,
                'statut' => 'en_attente',
                'total_articles' => $totalArticles,
                'total_quantite' => $totalQuantite,
                'montant_total' => $montantTotal,
                'cree_par' => auth()->user()->name ?? null,
            ]);

            foreach ($validated['lignes'] as $ligne) {
                $medicament = Medicament::find($ligne['medicament_id']);
                $fiche->lignes()->create([
                    'medicament_id' => $ligne['medicament_id'],
                    'nom' => $medicament->nom,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                ]);
            }

            return $fiche;
        });

        return redirect()->route('pharmacie.approvisionnements')
            ->with('success', 'Commande créée avec succès');
    }

    public function valider(FicheApprovisionnement $approvisionnement)
    {
        if ($approvisionnement->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette commande a déjà été traitée.');
        }

        DB::transaction(function () use ($approvisionnement) {
            $approvisionnement->update([
                'statut' => 'validee',
                'date_reception' => now(),
            ]);

            foreach ($approvisionnement->lignes as $ligne) {
                // Update stock
                $medicament = Medicament::find($ligne->medicament_id);
                $medicament->increment('stock', $ligne->quantite);

                // Record movement
                MouvementStock::create([
                    'medicament_id' => $ligne->medicament_id,
                    'type' => 'entree',
                    'quantite' => $ligne->quantite,
                    'motif' => 'Approvisionnement #' . $approvisionnement->numero . ' - ' . $approvisionnement->fournisseur,
                    'date' => now(),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Commande validée et stock mis à jour');
    }

    public function destroy(FicheApprovisionnement $approvisionnement)
    {
        if ($approvisionnement->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Impossible de supprimer une commande déjà validée.');
        }

        $approvisionnement->lignes()->delete();
        $approvisionnement->delete();

        return redirect()->back()->with('success', 'Commande supprimée');
    }

    public function show(FicheApprovisionnement $approvisionnement)
    {
        $approvisionnement->load('lignes.medicament');
        return response()->json([
            'id' => $approvisionnement->id,
            'numero' => $approvisionnement->numero,
            'fournisseur' => $approvisionnement->fournisseur,
            'date' => $approvisionnement->date ? $approvisionnement->date->format('d/m/Y') : null,
            'statut' => $approvisionnement->statut,
            'date_reception' => $approvisionnement->date_reception ? $approvisionnement->date_reception->format('d/m/Y') : null,
            'observations' => $approvisionnement->observations,
            'cree_par' => $approvisionnement->cree_par,
            'lignes' => $approvisionnement->lignes->map(fn($l) => [
                'medicament' => $l->medicament->nom ?? $l->nom,
                'quantite' => $l->quantite,
                'prix_unitaire' => $l->prix_unitaire,
                'montant' => $l->quantite * $l->prix_unitaire,
            ]),
            'total' => $approvisionnement->lignes->sum(fn($l) => $l->quantite * $l->prix_unitaire),
        ]);
    }
}
