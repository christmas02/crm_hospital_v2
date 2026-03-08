<?php

namespace Database\Seeders;

use App\Models\FicheApprovisionnement;
use App\Models\ApprovisionnementLigne;
use Illuminate\Database\Seeder;

class FicheApprovisionnementSeeder extends Seeder
{
    public function run()
    {
        $fiches = [
            [
                'id' => 1,
                'numero' => 'APP-2024-001',
                'date' => '2024-02-15',
                'fournisseur' => 'Pharma CI',
                'total_articles' => 2,
                'total_quantite' => 300,
                'montant_total' => 25000,
                'observations' => 'Commande mensuelle',
                'cree_par' => 'Pharmacien',
            ],
            [
                'id' => 2,
                'numero' => 'APP-2024-002',
                'date' => '2024-02-18',
                'fournisseur' => 'MedAfrique',
                'total_articles' => 1,
                'total_quantite' => 50,
                'montant_total' => 125000,
                'observations' => 'Réapprovisionnement urgent antipaludéens',
                'cree_par' => 'Pharmacien',
            ],
        ];

        foreach ($fiches as $fiche) {
            FicheApprovisionnement::create($fiche);
        }

        // Lignes d'approvisionnement
        $lignes = [
            ['fiche_approvisionnement_id' => 1, 'medicament_id' => 1, 'nom' => 'Paracétamol 500mg', 'quantite' => 200, 'prix_unitaire' => 50],
            ['fiche_approvisionnement_id' => 1, 'medicament_id' => 3, 'nom' => 'Amoxicilline 500mg', 'quantite' => 100, 'prix_unitaire' => 150],
            ['fiche_approvisionnement_id' => 2, 'medicament_id' => 4, 'nom' => 'Arthémether-Luméfantrine', 'quantite' => 50, 'prix_unitaire' => 2500],
        ];

        foreach ($lignes as $ligne) {
            ApprovisionnementLigne::create($ligne);
        }
    }
}
