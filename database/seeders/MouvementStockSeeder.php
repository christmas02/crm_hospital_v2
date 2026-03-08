<?php

namespace Database\Seeders;

use App\Models\MouvementStock;
use Illuminate\Database\Seeder;

class MouvementStockSeeder extends Seeder
{
    public function run()
    {
        $mouvements = [
            ['id' => 1, 'medicament_id' => 1, 'type' => 'entree', 'quantite' => 200, 'date' => '2024-02-15', 'motif' => 'Réapprovisionnement'],
            ['id' => 2, 'medicament_id' => 4, 'type' => 'sortie', 'quantite' => 24, 'date' => '2024-02-20', 'motif' => 'Prescription patient #1'],
            ['id' => 3, 'medicament_id' => 5, 'type' => 'sortie', 'quantite' => 30, 'date' => '2024-02-20', 'motif' => 'Prescription patient #2'],
            ['id' => 4, 'medicament_id' => 3, 'type' => 'entree', 'quantite' => 100, 'date' => '2024-02-18', 'motif' => 'Commande fournisseur'],
        ];

        foreach ($mouvements as $mouvement) {
            MouvementStock::create($mouvement);
        }
    }
}
