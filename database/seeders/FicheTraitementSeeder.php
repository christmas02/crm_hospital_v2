<?php

namespace Database\Seeders;

use App\Models\FicheTraitement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FicheTraitementSeeder extends Seeder
{
    public function run()
    {
        $fiches = [
            ['id' => 1, 'consultation_id' => 1, 'patient_id' => 1, 'medecin_id' => 1, 'date' => '2024-02-20', 'observations' => 'Patient présentant fièvre 39.2°C et céphalées depuis 3 jours. TDR positif Pf.', 'total_facturable' => 22000],
            ['id' => 2, 'consultation_id' => 2, 'patient_id' => 2, 'medecin_id' => 3, 'date' => '2024-02-20', 'observations' => 'Douleurs thoraciques atypiques. ECG normal. TA 150/95. Majoration traitement HTA.', 'total_facturable' => 40000],
            ['id' => 3, 'consultation_id' => 3, 'patient_id' => 3, 'medecin_id' => 2, 'date' => '2024-02-20', 'observations' => 'G2P0, 28 SA. Écho: présentation céphalique, liquide normal, BIP conforme. Mouvements actifs.', 'total_facturable' => 60000],
            ['id' => 4, 'consultation_id' => 4, 'patient_id' => 4, 'medecin_id' => 1, 'date' => '2024-02-20', 'observations' => 'Diabète type 2 équilibré. Glycémie à jeun 1.12g/L. HbA1c 6.8%. Continuer Metformine.', 'total_facturable' => 18000],
            ['id' => 5, 'consultation_id' => 5, 'patient_id' => 5, 'medecin_id' => 4, 'date' => '2024-02-20', 'observations' => 'Rappel DTP effectué. Pas de réaction allergique. Prochain rappel dans 1 an.', 'total_facturable' => 20000],
            ['id' => 6, 'consultation_id' => 19, 'patient_id' => 13, 'medecin_id' => 4, 'date' => '2024-02-17', 'observations' => 'Otite moyenne aiguë droite. Tympan bombé, hyperémique. Antibiotiques 7 jours.', 'total_facturable' => 15000],
            ['id' => 7, 'consultation_id' => 23, 'patient_id' => 19, 'medecin_id' => 3, 'date' => '2024-02-14', 'observations' => 'Insuffisance cardiaque décompensée. OAP. Furosémide IV. Hospitalisation urgente.', 'total_facturable' => 70000],
            ['id' => 8, 'consultation_id' => 28, 'patient_id' => 24, 'medecin_id' => 5, 'date' => '2024-02-19', 'observations' => 'AVP moto. Fracture tibia droit 1/3 moyen. Plâtre cruro-pédieux. Chirurgie programmée.', 'total_facturable' => 89000],
            ['id' => 9, 'consultation_id' => 35, 'patient_id' => 14, 'medecin_id' => 4, 'date' => '2024-02-20', 'observations' => 'Convulsions fébriles simples. T°40.1°C. TDR négatif. NFS normale. Hospitalisation surveillance.', 'total_facturable' => 55000],
            ['id' => 10, 'consultation_id' => 30, 'patient_id' => 26, 'medecin_id' => 1, 'date' => '2024-02-18', 'observations' => 'Check-up annuel. Bilan complet normal. RAS. Prochain contrôle dans 1 an.', 'total_facturable' => 63000],
        ];

        foreach ($fiches as $fiche) {
            FicheTraitement::create($fiche);
        }

        // Pivot data for actes
        $actes = [
            // Fiche 1
            ['fiche_traitement_id' => 1, 'acte_medical_id' => 1, 'nom' => 'Consultation générale', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 1, 'acte_medical_id' => 10, 'nom' => 'Test paludisme (TDR)', 'prix' => 5000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 1, 'acte_medical_id' => 13, 'nom' => 'Injection', 'prix' => 2000, 'quantite' => 1, 'facturable' => true],
            // Fiche 2
            ['fiche_traitement_id' => 2, 'acte_medical_id' => 2, 'nom' => 'Consultation spécialisée', 'prix' => 25000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 2, 'acte_medical_id' => 6, 'nom' => 'Électrocardiogramme (ECG)', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 2, 'acte_medical_id' => 12, 'nom' => 'Prise de tension', 'prix' => 0, 'quantite' => 1, 'facturable' => false],
            // Fiche 3
            ['fiche_traitement_id' => 3, 'acte_medical_id' => 2, 'nom' => 'Consultation spécialisée', 'prix' => 25000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 3, 'acte_medical_id' => 7, 'nom' => 'Échographie', 'prix' => 35000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 3, 'acte_medical_id' => 12, 'nom' => 'Prise de tension', 'prix' => 0, 'quantite' => 1, 'facturable' => false],
            // Fiche 4
            ['fiche_traitement_id' => 4, 'acte_medical_id' => 1, 'nom' => 'Consultation générale', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 4, 'acte_medical_id' => 11, 'nom' => 'Glycémie', 'prix' => 3000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 4, 'acte_medical_id' => 12, 'nom' => 'Prise de tension', 'prix' => 0, 'quantite' => 1, 'facturable' => false],
            // Fiche 5
            ['fiche_traitement_id' => 5, 'acte_medical_id' => 1, 'nom' => 'Consultation générale', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 5, 'acte_medical_id' => 18, 'nom' => 'Vaccination', 'prix' => 5000, 'quantite' => 1, 'facturable' => true],
            // Fiche 6
            ['fiche_traitement_id' => 6, 'acte_medical_id' => 1, 'nom' => 'Consultation générale', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            // Fiche 7
            ['fiche_traitement_id' => 7, 'acte_medical_id' => 3, 'nom' => 'Consultation urgence', 'prix' => 20000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 7, 'acte_medical_id' => 6, 'nom' => 'Électrocardiogramme (ECG)', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 7, 'acte_medical_id' => 4, 'nom' => 'Bilan sanguin complet', 'prix' => 25000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 7, 'acte_medical_id' => 14, 'nom' => 'Perfusion', 'prix' => 5000, 'quantite' => 2, 'facturable' => true],
            // Fiche 8
            ['fiche_traitement_id' => 8, 'acte_medical_id' => 3, 'nom' => 'Consultation urgence', 'prix' => 20000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 8, 'acte_medical_id' => 8, 'nom' => 'Radiographie', 'prix' => 20000, 'quantite' => 2, 'facturable' => true],
            ['fiche_traitement_id' => 8, 'acte_medical_id' => 13, 'nom' => 'Injection', 'prix' => 2000, 'quantite' => 2, 'facturable' => true],
            ['fiche_traitement_id' => 8, 'acte_medical_id' => 20, 'nom' => 'Pose de plâtre', 'prix' => 25000, 'quantite' => 1, 'facturable' => true],
            // Fiche 9
            ['fiche_traitement_id' => 9, 'acte_medical_id' => 3, 'nom' => 'Consultation urgence', 'prix' => 20000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 9, 'acte_medical_id' => 10, 'nom' => 'Test paludisme (TDR)', 'prix' => 5000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 9, 'acte_medical_id' => 4, 'nom' => 'Bilan sanguin complet', 'prix' => 25000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 9, 'acte_medical_id' => 14, 'nom' => 'Perfusion', 'prix' => 5000, 'quantite' => 1, 'facturable' => true],
            // Fiche 10
            ['fiche_traitement_id' => 10, 'acte_medical_id' => 1, 'nom' => 'Consultation générale', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 10, 'acte_medical_id' => 4, 'nom' => 'Bilan sanguin complet', 'prix' => 25000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 10, 'acte_medical_id' => 5, 'nom' => 'Analyse urinaire', 'prix' => 8000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 10, 'acte_medical_id' => 6, 'nom' => 'Électrocardiogramme (ECG)', 'prix' => 15000, 'quantite' => 1, 'facturable' => true],
            ['fiche_traitement_id' => 10, 'acte_medical_id' => 12, 'nom' => 'Prise de tension', 'prix' => 0, 'quantite' => 1, 'facturable' => false],
        ];

        DB::table('fiche_traitement_actes')->insert($actes);
    }
}
