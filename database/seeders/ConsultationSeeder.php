<?php

namespace Database\Seeders;

use App\Models\Consultation;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    public function run()
    {
        $consultations = [
            // Consultations du jour - 20 février 2024
            ['id' => 1, 'patient_id' => 1, 'medecin_id' => 1, 'date' => '2024-02-20', 'heure' => '08:30', 'motif' => 'Fièvre et maux de tête', 'diagnostic' => 'Paludisme simple', 'statut' => 'termine', 'notes' => 'Traitement antipaludéen prescrit. TDR positif.'],
            ['id' => 2, 'patient_id' => 2, 'medecin_id' => 3, 'date' => '2024-02-20', 'heure' => '09:00', 'motif' => 'Douleurs thoraciques', 'diagnostic' => 'Hypertension légère', 'statut' => 'termine', 'notes' => 'ECG normal, suivi recommandé. TA 150/95'],
            ['id' => 3, 'patient_id' => 3, 'medecin_id' => 2, 'date' => '2024-02-20', 'heure' => '09:30', 'motif' => 'Suivi grossesse', 'diagnostic' => 'Grossesse 28 semaines - RAS', 'statut' => 'termine', 'notes' => 'Écho normale, bébé en bonne santé'],
            ['id' => 4, 'patient_id' => 4, 'medecin_id' => 1, 'date' => '2024-02-20', 'heure' => '10:00', 'motif' => 'Contrôle diabète', 'diagnostic' => 'Diabète type 2 équilibré', 'statut' => 'termine', 'notes' => 'HbA1c 6.8%, continuer traitement'],
            ['id' => 5, 'patient_id' => 5, 'medecin_id' => 4, 'date' => '2024-02-20', 'heure' => '10:30', 'motif' => 'Vaccination rappel', 'diagnostic' => 'Vaccination effectuée', 'statut' => 'termine', 'notes' => 'DTP rappel administré'],
            ['id' => 6, 'patient_id' => 7, 'medecin_id' => 1, 'date' => '2024-02-20', 'heure' => '11:00', 'motif' => 'Douleurs articulaires', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 7, 'patient_id' => 8, 'medecin_id' => 3, 'date' => '2024-02-20', 'heure' => '11:30', 'motif' => 'Bilan cardiaque annuel', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 8, 'patient_id' => 9, 'medecin_id' => 2, 'date' => '2024-02-20', 'heure' => '14:00', 'motif' => 'Consultation prénatale', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 9, 'patient_id' => 11, 'medecin_id' => 4, 'date' => '2024-02-20', 'heure' => '14:30', 'motif' => 'Fièvre enfant 18 mois', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 10, 'patient_id' => 12, 'medecin_id' => 4, 'date' => '2024-02-20', 'heure' => '15:00', 'motif' => 'Toux persistante', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 11, 'patient_id' => 15, 'medecin_id' => 2, 'date' => '2024-02-20', 'heure' => '15:30', 'motif' => 'Échographie 2ème trimestre', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 12, 'patient_id' => 18, 'medecin_id' => 1, 'date' => '2024-02-20', 'heure' => '16:00', 'motif' => 'Douleurs lombaires', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 13, 'patient_id' => 21, 'medecin_id' => 3, 'date' => '2024-02-20', 'heure' => '16:30', 'motif' => 'Suivi hypertension', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 14, 'patient_id' => 29, 'medecin_id' => 1, 'date' => '2024-02-20', 'heure' => '17:00', 'motif' => 'Première consultation - fatigue', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            ['id' => 15, 'patient_id' => 30, 'medecin_id' => 2, 'date' => '2024-02-20', 'heure' => '17:30', 'motif' => 'Règles irrégulières', 'diagnostic' => '', 'statut' => 'en_attente', 'notes' => ''],
            // Consultations passées - Historique
            ['id' => 16, 'patient_id' => 1, 'medecin_id' => 1, 'date' => '2024-02-15', 'heure' => '09:00', 'motif' => 'Grippe', 'diagnostic' => 'Syndrome grippal', 'statut' => 'termine', 'notes' => 'Repos et hydratation recommandés'],
            ['id' => 17, 'patient_id' => 2, 'medecin_id' => 3, 'date' => '2024-02-10', 'heure' => '10:00', 'motif' => 'Contrôle tension', 'diagnostic' => 'TA normalisée', 'statut' => 'termine', 'notes' => 'Continuer traitement Amlodipine'],
            ['id' => 18, 'patient_id' => 11, 'medecin_id' => 4, 'date' => '2024-02-18', 'heure' => '09:30', 'motif' => 'Vaccin ROR', 'diagnostic' => 'Vaccination effectuée', 'statut' => 'termine', 'notes' => 'ROR première dose'],
            ['id' => 19, 'patient_id' => 13, 'medecin_id' => 4, 'date' => '2024-02-17', 'heure' => '10:00', 'motif' => 'Otite', 'diagnostic' => 'Otite moyenne aiguë', 'statut' => 'termine', 'notes' => 'Antibiotiques prescrits 7 jours'],
            ['id' => 20, 'patient_id' => 15, 'medecin_id' => 2, 'date' => '2024-02-12', 'heure' => '11:00', 'motif' => 'Première consultation grossesse', 'diagnostic' => 'Grossesse confirmée 12 SA', 'statut' => 'termine', 'notes' => 'Bilan sanguin prescrit'],
            ['id' => 21, 'patient_id' => 16, 'medecin_id' => 2, 'date' => '2024-02-08', 'heure' => '14:00', 'motif' => 'Suivi grossesse 20 SA', 'diagnostic' => 'Grossesse normale', 'statut' => 'termine', 'notes' => 'Écho morpho prévue'],
            ['id' => 22, 'patient_id' => 18, 'medecin_id' => 1, 'date' => '2024-02-05', 'heure' => '09:00', 'motif' => 'Douleurs thoraciques', 'diagnostic' => 'Douleurs musculaires intercostales', 'statut' => 'termine', 'notes' => 'Anti-inflammatoires prescrits'],
            ['id' => 23, 'patient_id' => 19, 'medecin_id' => 3, 'date' => '2024-02-14', 'heure' => '10:30', 'motif' => 'Essoufflement', 'diagnostic' => 'Insuffisance cardiaque décompensée', 'statut' => 'termine', 'notes' => 'Hospitalisation recommandée'],
            ['id' => 24, 'patient_id' => 20, 'medecin_id' => 1, 'date' => '2024-02-19', 'heure' => '11:00', 'motif' => 'Contrôle prostate', 'diagnostic' => 'HBP stable', 'statut' => 'termine', 'notes' => 'PSA normal, continuer surveillance'],
            ['id' => 25, 'patient_id' => 21, 'medecin_id' => 3, 'date' => '2024-02-06', 'heure' => '09:00', 'motif' => 'Palpitations', 'diagnostic' => 'Extrasystoles bénignes', 'statut' => 'termine', 'notes' => 'Holter prescrit, éviter café'],
            ['id' => 26, 'patient_id' => 22, 'medecin_id' => 1, 'date' => '2024-02-13', 'heure' => '15:00', 'motif' => 'Contrôle glycémie', 'diagnostic' => 'Diabète déséquilibré', 'statut' => 'termine', 'notes' => 'Ajustement traitement insuline'],
            ['id' => 27, 'patient_id' => 23, 'medecin_id' => 3, 'date' => '2024-02-11', 'heure' => '16:00', 'motif' => 'Suivi post-infarctus', 'diagnostic' => 'Évolution favorable', 'statut' => 'termine', 'notes' => 'Rééducation cardiaque en cours'],
            ['id' => 28, 'patient_id' => 24, 'medecin_id' => 5, 'date' => '2024-02-19', 'heure' => '08:00', 'motif' => 'Accident de circulation', 'diagnostic' => 'Fracture tibia droit', 'statut' => 'termine', 'notes' => 'Chirurgie programmée'],
            ['id' => 29, 'patient_id' => 25, 'medecin_id' => 1, 'date' => '2024-02-19', 'heure' => '14:00', 'motif' => 'Migraine sévère', 'diagnostic' => 'Migraine avec aura', 'statut' => 'termine', 'notes' => 'Traitement de fond initié'],
            ['id' => 30, 'patient_id' => 26, 'medecin_id' => 1, 'date' => '2024-02-18', 'heure' => '10:00', 'motif' => 'Check-up annuel', 'diagnostic' => 'Bilan normal', 'statut' => 'termine', 'notes' => 'RAS, prochain contrôle dans 1 an'],
            ['id' => 31, 'patient_id' => 27, 'medecin_id' => 2, 'date' => '2024-02-16', 'heure' => '11:30', 'motif' => 'Douleurs pelviennes', 'diagnostic' => 'Kyste ovarien fonctionnel', 'statut' => 'termine', 'notes' => 'Échographie de contrôle dans 3 mois'],
            ['id' => 32, 'patient_id' => 28, 'medecin_id' => 1, 'date' => '2024-02-15', 'heure' => '15:30', 'motif' => 'Toux chronique', 'diagnostic' => 'Bronchite chronique', 'statut' => 'termine', 'notes' => 'Arrêt tabac conseillé, traitement prescrit'],
            ['id' => 33, 'patient_id' => 31, 'medecin_id' => 6, 'date' => '2024-02-17', 'heure' => '09:00', 'motif' => 'Éruption cutanée', 'diagnostic' => 'Eczéma atopique', 'statut' => 'termine', 'notes' => 'Dermocorticoïdes prescrits'],
            ['id' => 34, 'patient_id' => 32, 'medecin_id' => 2, 'date' => '2024-02-14', 'heure' => '14:30', 'motif' => 'Ménopause - bouffées de chaleur', 'diagnostic' => 'Syndrome climatérique', 'statut' => 'termine', 'notes' => 'THS discuté, phytothérapie proposée'],
            // Urgences récentes
            ['id' => 35, 'patient_id' => 14, 'medecin_id' => 4, 'date' => '2024-02-20', 'heure' => '07:30', 'motif' => 'Urgence - Convulsions fébriles', 'diagnostic' => 'Convulsions fébriles simples', 'statut' => 'termine', 'notes' => 'Hospitalisation pour surveillance 24h'],
            ['id' => 36, 'patient_id' => 17, 'medecin_id' => 2, 'date' => '2024-02-19', 'heure' => '23:00', 'motif' => 'Urgence - Contractions prématurées', 'diagnostic' => 'Menace accouchement prématuré', 'statut' => 'termine', 'notes' => 'Tocolyse, hospitalisation'],
            // Consultation en cours
            ['id' => 37, 'patient_id' => 6, 'medecin_id' => 3, 'date' => '2024-02-20', 'heure' => '08:00', 'motif' => 'Visite hospitalisation', 'diagnostic' => 'Insuffisance cardiaque - amélioration', 'statut' => 'en_cours', 'notes' => 'Diurétiques ajustés'],
        ];

        foreach ($consultations as $consultation) {
            Consultation::create($consultation);
        }
    }
}
