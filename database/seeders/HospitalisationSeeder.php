<?php

namespace Database\Seeders;

use App\Models\Hospitalisation;
use Illuminate\Database\Seeder;

class HospitalisationSeeder extends Seeder
{
    public function run()
    {
        $hospitalisations = [
            // Hospitalisations en cours
            ['id' => 1, 'patient_id' => 3, 'chambre_id' => 1, 'medecin_id' => 2, 'date_admission' => '2024-02-18', 'date_sortie' => null, 'motif' => 'Surveillance grossesse à risque - MAP', 'statut' => 'en_cours'],
            ['id' => 2, 'patient_id' => 6, 'chambre_id' => 3, 'medecin_id' => 3, 'date_admission' => '2024-02-15', 'date_sortie' => null, 'motif' => 'Insuffisance cardiaque décompensée', 'statut' => 'en_cours'],
            ['id' => 3, 'patient_id' => 10, 'chambre_id' => 4, 'medecin_id' => 5, 'date_admission' => '2024-02-19', 'date_sortie' => null, 'motif' => 'Post-opératoire hernie inguinale', 'statut' => 'en_cours'],
            ['id' => 4, 'patient_id' => 14, 'chambre_id' => 2, 'medecin_id' => 4, 'date_admission' => '2024-02-20', 'date_sortie' => null, 'motif' => 'Convulsions fébriles - Surveillance 24h', 'statut' => 'en_cours'],
            ['id' => 5, 'patient_id' => 17, 'chambre_id' => 7, 'medecin_id' => 2, 'date_admission' => '2024-02-19', 'date_sortie' => null, 'motif' => 'Menace accouchement prématuré 34 SA', 'statut' => 'en_cours'],
            ['id' => 6, 'patient_id' => 19, 'chambre_id' => 5, 'medecin_id' => 3, 'date_admission' => '2024-02-14', 'date_sortie' => null, 'motif' => 'Insuffisance cardiaque - Soins palliatifs', 'statut' => 'en_cours'],
            ['id' => 7, 'patient_id' => 24, 'chambre_id' => 8, 'medecin_id' => 5, 'date_admission' => '2024-02-19', 'date_sortie' => null, 'motif' => 'Fracture tibia - Attente chirurgie', 'statut' => 'en_cours'],
            // Hospitalisations terminées
            ['id' => 8, 'patient_id' => 1, 'chambre_id' => 2, 'medecin_id' => 1, 'date_admission' => '2024-02-01', 'date_sortie' => '2024-02-03', 'motif' => 'Paludisme sévère', 'statut' => 'termine'],
            ['id' => 9, 'patient_id' => 8, 'chambre_id' => 3, 'medecin_id' => 1, 'date_admission' => '2024-01-25', 'date_sortie' => '2024-01-28', 'motif' => 'Gastro-entérite aiguë - Déshydratation', 'statut' => 'termine'],
            ['id' => 10, 'patient_id' => 22, 'chambre_id' => 4, 'medecin_id' => 1, 'date_admission' => '2024-02-05', 'date_sortie' => '2024-02-12', 'motif' => 'Déséquilibre diabétique sévère', 'statut' => 'termine'],
        ];

        foreach ($hospitalisations as $hospitalisation) {
            Hospitalisation::create($hospitalisation);
        }
    }
}
