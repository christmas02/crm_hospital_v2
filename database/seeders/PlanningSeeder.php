<?php

namespace Database\Seeders;

use App\Models\Planning;
use Illuminate\Database\Seeder;

class PlanningSeeder extends Seeder
{
    public function run()
    {
        $planning = [
            ['medecin_id' => 1, 'jour' => 'lundi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 1, 'jour' => 'mardi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 1, 'jour' => 'mercredi', 'debut' => '08:00', 'fin' => '12:00'],
            ['medecin_id' => 1, 'jour' => 'jeudi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 1, 'jour' => 'vendredi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 2, 'jour' => 'lundi', 'debut' => '09:00', 'fin' => '17:00'],
            ['medecin_id' => 2, 'jour' => 'mardi', 'debut' => '09:00', 'fin' => '17:00'],
            ['medecin_id' => 2, 'jour' => 'jeudi', 'debut' => '09:00', 'fin' => '17:00'],
            ['medecin_id' => 2, 'jour' => 'vendredi', 'debut' => '09:00', 'fin' => '14:00'],
            ['medecin_id' => 3, 'jour' => 'lundi', 'debut' => '08:00', 'fin' => '14:00'],
            ['medecin_id' => 3, 'jour' => 'mercredi', 'debut' => '08:00', 'fin' => '14:00'],
            ['medecin_id' => 3, 'jour' => 'vendredi', 'debut' => '08:00', 'fin' => '14:00'],
            ['medecin_id' => 4, 'jour' => 'lundi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 4, 'jour' => 'mardi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 4, 'jour' => 'mercredi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 4, 'jour' => 'jeudi', 'debut' => '08:00', 'fin' => '16:00'],
            ['medecin_id' => 5, 'jour' => 'mardi', 'debut' => '07:00', 'fin' => '15:00'],
            ['medecin_id' => 5, 'jour' => 'jeudi', 'debut' => '07:00', 'fin' => '15:00'],
            ['medecin_id' => 6, 'jour' => 'lundi', 'debut' => '10:00', 'fin' => '16:00'],
            ['medecin_id' => 6, 'jour' => 'mercredi', 'debut' => '10:00', 'fin' => '16:00'],
        ];

        foreach ($planning as $p) {
            Planning::create($p);
        }
    }
}
