<?php

namespace Database\Seeders;

use App\Models\SigneVital;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class SigneVitalSeeder extends Seeder
{
    public function run()
    {
        $consultations = Consultation::where('statut', 'termine')->with('patient')->limit(15)->get();

        foreach ($consultations as $consultation) {
            SigneVital::create([
                'patient_id' => $consultation->patient_id,
                'consultation_id' => $consultation->id,
                'pris_par' => 1,
                'temperature' => round(rand(365, 390) / 10, 1),
                'tension_systolique' => (string) rand(10, 16),
                'tension_diastolique' => (string) rand(6, 10),
                'pouls' => rand(55, 110),
                'frequence_respiratoire' => rand(12, 22),
                'saturation_o2' => rand(92, 100),
                'poids' => round(rand(450, 950) / 10, 1),
                'taille' => round(rand(1500, 1900) / 10, 1),
                'imc' => null,
                'glycemie' => rand(70, 180),
                'created_at' => $consultation->date,
                'updated_at' => $consultation->date,
            ]);
        }

        // Calculate IMC for each
        SigneVital::all()->each(function ($sv) {
            if ($sv->poids && $sv->taille) {
                $tailleM = $sv->taille / 100;
                $sv->imc = round($sv->poids / ($tailleM * $tailleM), 1);
                $sv->save();
            }
        });
    }
}
