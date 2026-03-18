<?php

namespace Database\Seeders;

use App\Models\Vaccination;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class VaccinationSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();
        $vaccins = [
            ['vaccin' => 'BCG', 'maladie' => 'Tuberculose'],
            ['vaccin' => 'DTC-HepB-Hib', 'maladie' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B'],
            ['vaccin' => 'VPO', 'maladie' => 'Poliomyélite'],
            ['vaccin' => 'ROR', 'maladie' => 'Rougeole, Oreillons, Rubéole'],
            ['vaccin' => 'Fièvre jaune', 'maladie' => 'Fièvre jaune'],
            ['vaccin' => 'Hépatite B', 'maladie' => 'Hépatite B'],
            ['vaccin' => 'Pneumocoque', 'maladie' => 'Infections à pneumocoque'],
            ['vaccin' => 'Méningite A', 'maladie' => 'Méningite'],
            ['vaccin' => 'COVID-19', 'maladie' => 'COVID-19'],
            ['vaccin' => 'Grippe saisonnière', 'maladie' => 'Grippe'],
            ['vaccin' => 'Tétanos', 'maladie' => 'Tétanos'],
        ];
        $doses = ['1ère dose', '2ème dose', '3ème dose', 'Rappel'];
        $sites = ['Bras gauche', 'Bras droit', 'Cuisse gauche', 'Cuisse droite'];

        foreach ($patients->random(min(20, $patients->count())) as $patient) {
            $nbVaccins = rand(2, 5);
            $selectedVaccins = collect($vaccins)->random($nbVaccins);

            foreach ($selectedVaccins as $v) {
                $dateAdmin = now()->subDays(rand(30, 1500));
                Vaccination::create([
                    'patient_id' => $patient->id,
                    'vaccin' => $v['vaccin'],
                    'maladie' => $v['maladie'],
                    'date_administration' => $dateAdmin,
                    'dose' => $doses[rand(0, 3)],
                    'lot' => 'LOT-' . strtoupper(substr(md5(rand()), 0, 6)),
                    'site_injection' => $sites[rand(0, 3)],
                    'prochain_rappel' => rand(0, 1) ? $dateAdmin->copy()->addYear() : null,
                    'administre_par' => 1,
                    'notes' => null,
                ]);
            }
        }
    }
}
