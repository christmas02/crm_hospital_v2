<?php

namespace Database\Seeders;

use App\Models\Rendezvous;
use Illuminate\Database\Seeder;

class RendezvousSeeder extends Seeder
{
    public function run()
    {
        $rendezvous = [
            ['id' => 1, 'patient_id' => 1, 'medecin_id' => 1, 'date' => '2024-02-25', 'heure' => '09:00', 'motif' => 'Contrôle post-traitement', 'statut' => 'confirme'],
            ['id' => 2, 'patient_id' => 3, 'medecin_id' => 2, 'date' => '2024-03-05', 'heure' => '10:00', 'motif' => 'Suivi grossesse', 'statut' => 'confirme'],
            ['id' => 3, 'patient_id' => 2, 'medecin_id' => 3, 'date' => '2024-02-27', 'heure' => '11:00', 'motif' => 'ECG de contrôle', 'statut' => 'confirme'],
            ['id' => 4, 'patient_id' => 5, 'medecin_id' => 4, 'date' => '2024-02-22', 'heure' => '14:00', 'motif' => 'Rappel vaccin', 'statut' => 'en_attente'],
            ['id' => 5, 'patient_id' => 7, 'medecin_id' => 6, 'date' => '2024-02-23', 'heure' => '09:30', 'motif' => 'Consultation dermatologique', 'statut' => 'confirme'],
            ['id' => 6, 'patient_id' => 9, 'medecin_id' => 2, 'date' => '2024-02-28', 'heure' => '10:30', 'motif' => 'Échographie', 'statut' => 'confirme'],
        ];

        foreach ($rendezvous as $rdv) {
            Rendezvous::create($rdv);
        }
    }
}
