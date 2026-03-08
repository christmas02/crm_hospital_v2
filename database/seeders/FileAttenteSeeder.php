<?php

namespace Database\Seeders;

use App\Models\FileAttente;
use Illuminate\Database\Seeder;

class FileAttenteSeeder extends Seeder
{
    public function run()
    {
        $fileAttente = [
            // File attente Dr. Yao (Médecine générale)
            ['id' => 1, 'consultation_id' => 6, 'patient_id' => 7, 'medecin_id' => 1, 'heure_arrivee' => '10:45', 'position' => 1, 'statut' => 'en_attente'],
            ['id' => 2, 'consultation_id' => 12, 'patient_id' => 18, 'medecin_id' => 1, 'heure_arrivee' => '15:50', 'position' => 2, 'statut' => 'en_attente'],
            ['id' => 3, 'consultation_id' => 14, 'patient_id' => 29, 'medecin_id' => 1, 'heure_arrivee' => '16:45', 'position' => 3, 'statut' => 'en_attente'],
            // File attente Dr. Touré (Gynécologie)
            ['id' => 4, 'consultation_id' => 8, 'patient_id' => 9, 'medecin_id' => 2, 'heure_arrivee' => '13:50', 'position' => 1, 'statut' => 'en_attente'],
            ['id' => 5, 'consultation_id' => 11, 'patient_id' => 15, 'medecin_id' => 2, 'heure_arrivee' => '15:20', 'position' => 2, 'statut' => 'en_attente'],
            ['id' => 6, 'consultation_id' => 15, 'patient_id' => 30, 'medecin_id' => 2, 'heure_arrivee' => '17:15', 'position' => 3, 'statut' => 'en_attente'],
            // File attente Dr. Konaté (Cardiologie)
            ['id' => 7, 'consultation_id' => 7, 'patient_id' => 8, 'medecin_id' => 3, 'heure_arrivee' => '11:20', 'position' => 1, 'statut' => 'en_attente'],
            ['id' => 8, 'consultation_id' => 13, 'patient_id' => 21, 'medecin_id' => 3, 'heure_arrivee' => '16:20', 'position' => 2, 'statut' => 'en_attente'],
            // File attente Dr. Diabaté (Pédiatrie)
            ['id' => 9, 'consultation_id' => 9, 'patient_id' => 11, 'medecin_id' => 4, 'heure_arrivee' => '14:15', 'position' => 1, 'statut' => 'en_attente'],
            ['id' => 10, 'consultation_id' => 10, 'patient_id' => 12, 'medecin_id' => 4, 'heure_arrivee' => '14:50', 'position' => 2, 'statut' => 'en_attente'],
        ];

        foreach ($fileAttente as $fa) {
            FileAttente::create($fa);
        }
    }
}
