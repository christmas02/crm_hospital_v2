<?php

namespace Database\Seeders;

use App\Models\Ordonnance;
use App\Models\OrdonnanceMedicament;
use Illuminate\Database\Seeder;

class OrdonnanceSeeder extends Seeder
{
    public function run()
    {
        $ordonnances = [
            ['id' => 1, 'consultation_id' => 1, 'patient_id' => 1, 'medecin_id' => 1, 'date' => '2024-02-20', 'numero_retrait' => 'RET-2024-001', 'statut_dispensation' => 'remis', 'date_preparation' => '2024-02-20', 'date_remise' => '2024-02-20', 'remis_a' => 'Infirmier Konan', 'recommandations' => 'Repos et bonne hydratation. Revenir si fièvre persiste.'],
            ['id' => 2, 'consultation_id' => 2, 'patient_id' => 2, 'medecin_id' => 3, 'date' => '2024-02-20', 'numero_retrait' => 'RET-2024-002', 'statut_dispensation' => 'en_attente', 'date_preparation' => null, 'date_remise' => null, 'remis_a' => null, 'recommandations' => 'Contrôle tension dans 1 mois. Régime hyposodé.'],
            ['id' => 3, 'consultation_id' => 3, 'patient_id' => 3, 'medecin_id' => 2, 'date' => '2024-02-20', 'numero_retrait' => 'RET-2024-003', 'statut_dispensation' => 'prepare', 'date_preparation' => '2024-02-20', 'date_remise' => null, 'remis_a' => null, 'recommandations' => 'Continuer suivi prénatal. Prochain RDV dans 2 semaines.'],
            ['id' => 4, 'consultation_id' => 4, 'patient_id' => 4, 'medecin_id' => 1, 'date' => '2024-02-20', 'numero_retrait' => 'RET-2024-004', 'statut_dispensation' => 'en_attente', 'date_preparation' => null, 'date_remise' => null, 'remis_a' => null, 'recommandations' => 'Régime diabétique strict. Contrôle glycémie à jeun.'],
            ['id' => 5, 'consultation_id' => 5, 'patient_id' => 5, 'medecin_id' => 4, 'date' => '2024-02-20', 'numero_retrait' => 'RET-2024-005', 'statut_dispensation' => 'remis', 'date_preparation' => '2024-02-20', 'date_remise' => '2024-02-20', 'remis_a' => 'Infirmier Diallo', 'recommandations' => 'Post-vaccination. Surveiller température.'],
            ['id' => 6, 'consultation_id' => 19, 'patient_id' => 13, 'medecin_id' => 4, 'date' => '2024-02-17', 'numero_retrait' => 'RET-2024-006', 'statut_dispensation' => 'remis', 'date_preparation' => '2024-02-17', 'date_remise' => '2024-02-17', 'remis_a' => "Mère de l'enfant", 'recommandations' => "Otite moyenne. Consulter si pas d'amélioration dans 48h."],
            ['id' => 7, 'consultation_id' => 22, 'patient_id' => 18, 'medecin_id' => 1, 'date' => '2024-02-05', 'numero_retrait' => 'RET-2024-007', 'statut_dispensation' => 'remis', 'date_preparation' => '2024-02-05', 'date_remise' => '2024-02-05', 'remis_a' => 'Infirmier Konan', 'recommandations' => 'Douleurs intercostales. Éviter efforts physiques.'],
            ['id' => 8, 'consultation_id' => 25, 'patient_id' => 21, 'medecin_id' => 3, 'date' => '2024-02-06', 'numero_retrait' => 'RET-2024-008', 'statut_dispensation' => 'remis', 'date_preparation' => '2024-02-06', 'date_remise' => '2024-02-06', 'remis_a' => 'Infirmier Bamba', 'recommandations' => 'Palpitations. Éviter café et alcool. Holter dans 15 jours.'],
            ['id' => 9, 'consultation_id' => 29, 'patient_id' => 25, 'medecin_id' => 1, 'date' => '2024-02-19', 'numero_retrait' => 'RET-2024-009', 'statut_dispensation' => 'prepare', 'date_preparation' => '2024-02-19', 'date_remise' => null, 'remis_a' => null, 'recommandations' => 'Migraine. Tenir journal des crises. Éviter facteurs déclenchants.'],
            ['id' => 10, 'consultation_id' => 32, 'patient_id' => 28, 'medecin_id' => 1, 'date' => '2024-02-15', 'numero_retrait' => 'RET-2024-010', 'statut_dispensation' => 'remis', 'date_preparation' => '2024-02-15', 'date_remise' => '2024-02-15', 'remis_a' => 'Infirmier Konan', 'recommandations' => 'BPCO. Arrêt tabac impératif. Kinésithérapie respiratoire.'],
            ['id' => 11, 'consultation_id' => 33, 'patient_id' => 31, 'medecin_id' => 6, 'date' => '2024-02-17', 'numero_retrait' => 'RET-2024-011', 'statut_dispensation' => 'remis', 'date_preparation' => '2024-02-17', 'date_remise' => '2024-02-17', 'remis_a' => 'Patient directement', 'recommandations' => 'Eczéma. Éviter savons agressifs. Hydrater régulièrement.'],
            ['id' => 12, 'consultation_id' => 35, 'patient_id' => 14, 'medecin_id' => 4, 'date' => '2024-02-20', 'numero_retrait' => 'RET-2024-012', 'statut_dispensation' => 'en_attente', 'date_preparation' => null, 'date_remise' => null, 'remis_a' => null, 'recommandations' => 'Convulsions fébriles. Surveillance température. Urgences si récidive.'],
        ];

        foreach ($ordonnances as $ordonnance) {
            Ordonnance::create($ordonnance);
        }

        // Médicaments par ordonnance
        $medicaments = [
            // Ordonnance 1
            ['ordonnance_id' => 1, 'nom' => 'Arthémether-Luméfantrine', 'posologie' => '2 cp 2x/jour', 'duree' => '3 jours', 'quantite' => 12],
            ['ordonnance_id' => 1, 'nom' => 'Paracétamol 1g', 'posologie' => '1 cp 3x/jour si fièvre', 'duree' => '5 jours', 'quantite' => 15],
            // Ordonnance 2
            ['ordonnance_id' => 2, 'nom' => 'Amlodipine 5mg', 'posologie' => '1 cp le matin', 'duree' => '30 jours', 'quantite' => 30],
            ['ordonnance_id' => 2, 'nom' => 'Aspirine 100mg', 'posologie' => '1 cp le soir', 'duree' => '30 jours', 'quantite' => 30],
            // Ordonnance 3
            ['ordonnance_id' => 3, 'nom' => 'Fer + Acide folique', 'posologie' => '1 cp/jour', 'duree' => '30 jours', 'quantite' => 30],
            ['ordonnance_id' => 3, 'nom' => 'Calcium Vitamine D', 'posologie' => '1 cp/jour', 'duree' => '30 jours', 'quantite' => 30],
            // Ordonnance 4
            ['ordonnance_id' => 4, 'nom' => 'Metformine 1000mg', 'posologie' => '1 cp 2x/jour', 'duree' => '30 jours', 'quantite' => 60],
            ['ordonnance_id' => 4, 'nom' => 'Glibenclamide 5mg', 'posologie' => '1 cp le matin', 'duree' => '30 jours', 'quantite' => 30],
            // Ordonnance 5
            ['ordonnance_id' => 5, 'nom' => 'Paracétamol 500mg', 'posologie' => '1 cp si fièvre/douleur', 'duree' => '3 jours', 'quantite' => 6],
            // Ordonnance 6
            ['ordonnance_id' => 6, 'nom' => 'Amoxicilline 250mg/5ml', 'posologie' => '5ml 3x/jour', 'duree' => '7 jours', 'quantite' => 1],
            ['ordonnance_id' => 6, 'nom' => 'Paracétamol sirop', 'posologie' => '5ml si fièvre', 'duree' => '5 jours', 'quantite' => 1],
            // Ordonnance 7
            ['ordonnance_id' => 7, 'nom' => 'Diclofénac 50mg', 'posologie' => '1 cp 2x/jour après repas', 'duree' => '5 jours', 'quantite' => 10],
            ['ordonnance_id' => 7, 'nom' => 'Oméprazole 20mg', 'posologie' => '1 cp le matin à jeun', 'duree' => '5 jours', 'quantite' => 5],
            // Ordonnance 8
            ['ordonnance_id' => 8, 'nom' => 'Bisoprolol 5mg', 'posologie' => '1 cp le matin', 'duree' => '30 jours', 'quantite' => 30],
            ['ordonnance_id' => 8, 'nom' => 'Amlodipine 10mg', 'posologie' => '1 cp le soir', 'duree' => '30 jours', 'quantite' => 30],
            // Ordonnance 9
            ['ordonnance_id' => 9, 'nom' => 'Sumatriptan 50mg', 'posologie' => '1 cp au début de la crise', 'duree' => 'Selon besoin', 'quantite' => 6],
            ['ordonnance_id' => 9, 'nom' => 'Topiramate 25mg', 'posologie' => '1 cp le soir', 'duree' => '30 jours', 'quantite' => 30],
            // Ordonnance 10
            ['ordonnance_id' => 10, 'nom' => 'Salbutamol spray', 'posologie' => '2 bouffées si gêne', 'duree' => '1 mois', 'quantite' => 1],
            ['ordonnance_id' => 10, 'nom' => 'Fluticasone spray', 'posologie' => '2 bouffées 2x/jour', 'duree' => '1 mois', 'quantite' => 1],
            ['ordonnance_id' => 10, 'nom' => 'Carbocistéine 750mg', 'posologie' => '1 sachet 3x/jour', 'duree' => '10 jours', 'quantite' => 30],
            // Ordonnance 11
            ['ordonnance_id' => 11, 'nom' => 'Bétaméthasone crème', 'posologie' => 'Application 1x/jour', 'duree' => '7 jours', 'quantite' => 1],
            ['ordonnance_id' => 11, 'nom' => 'Cétirizine 10mg', 'posologie' => '1 cp le soir', 'duree' => '15 jours', 'quantite' => 15],
            ['ordonnance_id' => 11, 'nom' => 'Crème émolliente', 'posologie' => 'Application 2x/jour', 'duree' => '1 mois', 'quantite' => 1],
            // Ordonnance 12
            ['ordonnance_id' => 12, 'nom' => 'Paracétamol suppositoire 150mg', 'posologie' => '1 suppo si T>38.5°C', 'duree' => '3 jours', 'quantite' => 6],
            ['ordonnance_id' => 12, 'nom' => 'Diazépam rectal 5mg', 'posologie' => 'Si convulsion >3min', 'duree' => 'Urgence', 'quantite' => 2],
        ];

        foreach ($medicaments as $med) {
            OrdonnanceMedicament::create($med);
        }
    }
}
