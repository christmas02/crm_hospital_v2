<?php

namespace Database\Seeders;

use App\Models\Prescription;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    public function run()
    {
        $prescriptions = [
            [
                'id' => 1,
                'consultation_id' => 1,
                'patient_id' => 1,
                'medecin_id' => 1,
                'date' => '2024-02-20',
                'medicaments' => [
                    ['nom' => 'Arthémether-Luméfantrine', 'posologie' => '2 cp 2x/jour', 'duree' => '3 jours'],
                    ['nom' => 'Paracétamol 1g', 'posologie' => '1 cp 3x/jour si fièvre', 'duree' => '5 jours'],
                ],
            ],
            [
                'id' => 2,
                'consultation_id' => 2,
                'patient_id' => 2,
                'medecin_id' => 3,
                'date' => '2024-02-20',
                'medicaments' => [
                    ['nom' => 'Amlodipine 5mg', 'posologie' => '1 cp le matin', 'duree' => '30 jours'],
                    ['nom' => 'Aspirine 100mg', 'posologie' => '1 cp le soir', 'duree' => '30 jours'],
                ],
            ],
            [
                'id' => 3,
                'consultation_id' => 3,
                'patient_id' => 3,
                'medecin_id' => 2,
                'date' => '2024-02-20',
                'medicaments' => [
                    ['nom' => 'Fer + Acide folique', 'posologie' => '1 cp/jour', 'duree' => '30 jours'],
                    ['nom' => 'Calcium Vitamine D', 'posologie' => '1 cp/jour', 'duree' => '30 jours'],
                ],
            ],
        ];

        foreach ($prescriptions as $prescription) {
            Prescription::create($prescription);
        }
    }
}
