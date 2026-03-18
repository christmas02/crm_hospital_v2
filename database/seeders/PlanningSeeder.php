<?php

namespace Database\Seeders;

use App\Models\Planning;
use Illuminate\Database\Seeder;

class PlanningSeeder extends Seeder
{
    public function run()
    {
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        $medecins = \App\Models\Medecin::all();

        foreach ($medecins as $medecin) {
            // Each doctor works 4-5 days, with some variation
            $workDays = collect($jours)->random(rand(4, 5));
            foreach ($workDays as $jour) {
                $debut = ['07:00', '07:30', '08:00', '08:30'][rand(0, 3)];
                $fin = ['16:00', '16:30', '17:00', '17:30', '18:00'][rand(0, 4)];

                Planning::create([
                    'medecin_id' => $medecin->id,
                    'jour' => $jour,
                    'debut' => $debut,
                    'fin' => $fin,
                ]);
            }
        }
    }
}
