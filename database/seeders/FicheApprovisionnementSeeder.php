<?php

namespace Database\Seeders;

use App\Models\FicheApprovisionnement;
use App\Models\ApprovisionnementLigne;
use App\Models\Medicament;
use Illuminate\Database\Seeder;

class FicheApprovisionnementSeeder extends Seeder
{
    public function run()
    {
        $medicaments = Medicament::all();
        if ($medicaments->isEmpty()) return;

        $fournisseurs = ['DPCI', 'Copharmed', 'Laborex', 'Ubipharm'];

        for ($i = 0; $i < 3; $i++) {
            $selectedMeds = $medicaments->random(rand(3, min(6, $medicaments->count())));
            $totalQte = 0;
            $totalMontant = 0;

            $fiche = FicheApprovisionnement::create([
                'numero' => 'APP-' . date('Y') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'date' => now()->subDays(rand(5, 30)),
                'fournisseur' => $fournisseurs[array_rand($fournisseurs)],
                'total_articles' => $selectedMeds->count(),
                'total_quantite' => 0,
                'montant_total' => 0,
                'statut' => ['en_attente', 'validee', 'validee'][$i],
                'observations' => 'Commande de réapprovisionnement',
                'cree_par' => 'Pharmacien',
            ]);

            foreach ($selectedMeds as $med) {
                $qte = rand(20, 100);
                $prix = $med->prix_unitaire ?? rand(500, 5000);
                $totalQte += $qte;
                $totalMontant += $qte * $prix;

                ApprovisionnementLigne::create([
                    'fiche_approvisionnement_id' => $fiche->id,
                    'medicament_id' => $med->id,
                    'nom' => $med->nom,
                    'quantite' => $qte,
                    'prix_unitaire' => $prix,
                ]);
            }

            $fiche->update([
                'total_quantite' => $totalQte,
                'montant_total' => $totalMontant,
            ]);
        }
    }
}
