<?php

namespace Database\Seeders;

use App\Models\MouvementStock;
use App\Models\Medicament;
use Illuminate\Database\Seeder;

class MouvementStockSeeder extends Seeder
{
    public function run()
    {
        $medicaments = Medicament::all();
        if ($medicaments->isEmpty()) return;

        $motifsEntree = ['Réception commande', 'Approvisionnement', 'Don reçu', 'Transfert interne'];
        $motifsSortie = ['Dispensation patient', 'Périmé retiré', 'Transfert service', 'Ajustement inventaire'];

        foreach ($medicaments->random(min(8, $medicaments->count())) as $med) {
            // Entrée
            MouvementStock::create([
                'medicament_id' => $med->id,
                'type' => 'entree',
                'quantite' => rand(20, 100),
                'motif' => $motifsEntree[array_rand($motifsEntree)],
                'date' => now()->subDays(rand(5, 30)),
            ]);

            // Sortie
            MouvementStock::create([
                'medicament_id' => $med->id,
                'type' => 'sortie',
                'quantite' => rand(5, 20),
                'motif' => $motifsSortie[array_rand($motifsSortie)],
                'date' => now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
