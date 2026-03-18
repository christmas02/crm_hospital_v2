<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Medecin;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();
        $medecins = Medecin::all();

        $motifs = [
            'Fièvre et maux de tête', 'Douleurs thoraciques', 'Suivi grossesse',
            'Contrôle diabète', 'Vaccination rappel', 'Douleurs articulaires',
            'Bilan cardiaque annuel', 'Consultation prénatale', 'Toux persistante',
            'Douleurs lombaires', 'Suivi hypertension', 'Fatigue chronique',
            'Grippe saisonnière', 'Contrôle tension', 'Éruption cutanée',
            'Douleurs abdominales', 'Migraine sévère', 'Check-up annuel',
            'Contrôle glycémie', 'Essoufflement à l\'effort',
        ];

        $diagnostics = [
            'Paludisme simple', 'Hypertension légère', 'Syndrome grippal',
            'Diabète type 2 équilibré', 'Bronchite aiguë', 'Lombalgie mécanique',
            'Gastrite', 'Infection urinaire', 'Dermatite', 'Anémie modérée',
        ];

        $heures = ['07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];

        // Consultations d'aujourd'hui - en attente (file d'attente)
        for ($i = 0; $i < 6; $i++) {
            Consultation::create([
                'patient_id' => $patients->random()->id,
                'medecin_id' => $medecins->random()->id,
                'date' => today(),
                'heure' => $heures[$i],
                'motif' => $motifs[array_rand($motifs)],
                'statut' => 'en_attente',
            ]);
        }

        // Consultations d'aujourd'hui - en cours (1)
        Consultation::create([
            'patient_id' => $patients->random()->id,
            'medecin_id' => $medecins->first()->id, // Premier médecin pour le test
            'date' => today(),
            'heure' => '08:00',
            'motif' => 'Visite de contrôle',
            'statut' => 'en_cours',
            'diagnostic' => '',
        ]);

        // Consultations d'aujourd'hui - terminées
        for ($i = 0; $i < 4; $i++) {
            Consultation::create([
                'patient_id' => $patients->random()->id,
                'medecin_id' => $medecins->random()->id,
                'date' => today(),
                'heure' => $heures[$i],
                'motif' => $motifs[array_rand($motifs)],
                'diagnostic' => $diagnostics[array_rand($diagnostics)],
                'statut' => 'termine',
                'notes' => 'Traitement prescrit, suivi recommandé.',
            ]);
        }

        // Consultations des jours précédents (historique)
        for ($i = 0; $i < 20; $i++) {
            $daysAgo = rand(1, 60);
            Consultation::create([
                'patient_id' => $patients->random()->id,
                'medecin_id' => $medecins->random()->id,
                'date' => now()->subDays($daysAgo),
                'heure' => $heures[array_rand($heures)],
                'motif' => $motifs[array_rand($motifs)],
                'diagnostic' => $diagnostics[array_rand($diagnostics)],
                'statut' => 'termine',
                'notes' => 'Patient examiné. Traitement adapté.',
            ]);
        }
    }
}
