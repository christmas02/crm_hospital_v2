<?php

namespace Database\Seeders;

use App\Models\Chambre;
use Illuminate\Database\Seeder;

class ChambreSeeder extends Seeder
{
    public function run()
    {
        $chambres = [
            ['id' => 1, 'numero' => '101', 'etage' => 1, 'type' => 'individuelle', 'capacite' => 1, 'tarif_jour' => 50000, 'statut' => 'occupee', 'patient_id' => 3],
            ['id' => 2, 'numero' => '102', 'etage' => 1, 'type' => 'individuelle', 'capacite' => 1, 'tarif_jour' => 50000, 'statut' => 'libre', 'patient_id' => null],
            ['id' => 3, 'numero' => '103', 'etage' => 1, 'type' => 'double', 'capacite' => 2, 'tarif_jour' => 35000, 'statut' => 'occupee', 'patient_id' => 6],
            ['id' => 4, 'numero' => '201', 'etage' => 2, 'type' => 'individuelle', 'capacite' => 1, 'tarif_jour' => 50000, 'statut' => 'occupee', 'patient_id' => 10],
            ['id' => 5, 'numero' => '202', 'etage' => 2, 'type' => 'double', 'capacite' => 2, 'tarif_jour' => 35000, 'statut' => 'libre', 'patient_id' => null],
            ['id' => 6, 'numero' => '203', 'etage' => 2, 'type' => 'double', 'capacite' => 2, 'tarif_jour' => 35000, 'statut' => 'maintenance', 'patient_id' => null],
            ['id' => 7, 'numero' => '301', 'etage' => 3, 'type' => 'vip', 'capacite' => 1, 'tarif_jour' => 100000, 'statut' => 'libre', 'patient_id' => null],
            ['id' => 8, 'numero' => '302', 'etage' => 3, 'type' => 'vip', 'capacite' => 1, 'tarif_jour' => 100000, 'statut' => 'libre', 'patient_id' => null],
        ];

        foreach ($chambres as $chambre) {
            Chambre::create($chambre);
        }
    }
}
