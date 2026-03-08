<?php

namespace Database\Seeders;

use App\Models\Facture;
use App\Models\FactureLigne;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    public function run()
    {
        $factures = [
            ['id' => 1, 'numero' => 'FAC-2024-001', 'patient_id' => 1, 'consultation_id' => 1, 'fiche_traitement_id' => 1, 'date' => '2024-02-20', 'montant' => 22000, 'statut' => 'payee', 'envoye_par' => 'medecin', 'mode_paiement' => 'especes', 'date_paiement' => '2024-02-20'],
            ['id' => 2, 'numero' => 'FAC-2024-002', 'patient_id' => 2, 'consultation_id' => 2, 'fiche_traitement_id' => 2, 'date' => '2024-02-20', 'montant' => 40000, 'statut' => 'payee', 'envoye_par' => 'medecin', 'mode_paiement' => 'carte', 'date_paiement' => '2024-02-20'],
            ['id' => 3, 'numero' => 'FAC-2024-003', 'patient_id' => 13, 'consultation_id' => 19, 'fiche_traitement_id' => 6, 'date' => '2024-02-17', 'montant' => 15000, 'statut' => 'payee', 'envoye_par' => 'medecin', 'mode_paiement' => 'mobile_money', 'date_paiement' => '2024-02-17'],
            ['id' => 4, 'numero' => 'FAC-2024-004', 'patient_id' => 26, 'consultation_id' => 30, 'fiche_traitement_id' => 10, 'date' => '2024-02-18', 'montant' => 63000, 'statut' => 'payee', 'envoye_par' => 'medecin', 'mode_paiement' => 'carte', 'date_paiement' => '2024-02-18'],
            ['id' => 5, 'numero' => 'FAC-2024-005', 'patient_id' => 3, 'consultation_id' => 3, 'fiche_traitement_id' => 3, 'date' => '2024-02-20', 'montant' => 60000, 'statut' => 'en_attente', 'envoye_par' => 'medecin', 'mode_paiement' => null, 'date_paiement' => null],
            ['id' => 6, 'numero' => 'FAC-2024-006', 'patient_id' => 4, 'consultation_id' => 4, 'fiche_traitement_id' => 4, 'date' => '2024-02-20', 'montant' => 18000, 'statut' => 'en_attente', 'envoye_par' => 'medecin', 'mode_paiement' => null, 'date_paiement' => null],
            ['id' => 7, 'numero' => 'FAC-2024-007', 'patient_id' => 5, 'consultation_id' => 5, 'fiche_traitement_id' => 5, 'date' => '2024-02-20', 'montant' => 20000, 'statut' => 'en_attente', 'envoye_par' => 'medecin', 'mode_paiement' => null, 'date_paiement' => null],
            ['id' => 8, 'numero' => 'FAC-2024-008', 'patient_id' => 19, 'consultation_id' => 23, 'fiche_traitement_id' => 7, 'date' => '2024-02-14', 'montant' => 70000, 'statut' => 'en_attente', 'envoye_par' => 'medecin', 'mode_paiement' => null, 'date_paiement' => null],
            ['id' => 9, 'numero' => 'FAC-2024-009', 'patient_id' => 24, 'consultation_id' => 28, 'fiche_traitement_id' => 8, 'date' => '2024-02-19', 'montant' => 89000, 'statut' => 'en_attente', 'envoye_par' => 'medecin', 'mode_paiement' => null, 'date_paiement' => null],
            ['id' => 10, 'numero' => 'FAC-2024-010', 'patient_id' => 14, 'consultation_id' => 35, 'fiche_traitement_id' => 9, 'date' => '2024-02-20', 'montant' => 55000, 'statut' => 'en_attente', 'envoye_par' => 'medecin', 'mode_paiement' => null, 'date_paiement' => null],
        ];

        foreach ($factures as $facture) {
            Facture::create($facture);
        }

        // Lignes de factures
        $lignes = [
            // Facture 1
            ['facture_id' => 1, 'description' => 'Consultation générale', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            ['facture_id' => 1, 'description' => 'Test paludisme (TDR)', 'quantite' => 1, 'prix_unitaire' => 5000, 'total' => 5000],
            ['facture_id' => 1, 'description' => 'Injection', 'quantite' => 1, 'prix_unitaire' => 2000, 'total' => 2000],
            // Facture 2
            ['facture_id' => 2, 'description' => 'Consultation spécialisée', 'quantite' => 1, 'prix_unitaire' => 25000, 'total' => 25000],
            ['facture_id' => 2, 'description' => 'Électrocardiogramme (ECG)', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            // Facture 3
            ['facture_id' => 3, 'description' => 'Consultation générale', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            // Facture 4
            ['facture_id' => 4, 'description' => 'Consultation générale', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            ['facture_id' => 4, 'description' => 'Bilan sanguin complet', 'quantite' => 1, 'prix_unitaire' => 25000, 'total' => 25000],
            ['facture_id' => 4, 'description' => 'Analyse urinaire', 'quantite' => 1, 'prix_unitaire' => 8000, 'total' => 8000],
            ['facture_id' => 4, 'description' => 'Électrocardiogramme (ECG)', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            // Facture 5
            ['facture_id' => 5, 'description' => 'Consultation spécialisée', 'quantite' => 1, 'prix_unitaire' => 25000, 'total' => 25000],
            ['facture_id' => 5, 'description' => 'Échographie', 'quantite' => 1, 'prix_unitaire' => 35000, 'total' => 35000],
            // Facture 6
            ['facture_id' => 6, 'description' => 'Consultation générale', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            ['facture_id' => 6, 'description' => 'Glycémie', 'quantite' => 1, 'prix_unitaire' => 3000, 'total' => 3000],
            // Facture 7
            ['facture_id' => 7, 'description' => 'Consultation générale', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            ['facture_id' => 7, 'description' => 'Vaccination', 'quantite' => 1, 'prix_unitaire' => 5000, 'total' => 5000],
            // Facture 8
            ['facture_id' => 8, 'description' => 'Consultation urgence', 'quantite' => 1, 'prix_unitaire' => 20000, 'total' => 20000],
            ['facture_id' => 8, 'description' => 'Électrocardiogramme (ECG)', 'quantite' => 1, 'prix_unitaire' => 15000, 'total' => 15000],
            ['facture_id' => 8, 'description' => 'Bilan sanguin complet', 'quantite' => 1, 'prix_unitaire' => 25000, 'total' => 25000],
            ['facture_id' => 8, 'description' => 'Perfusion', 'quantite' => 2, 'prix_unitaire' => 5000, 'total' => 10000],
            // Facture 9
            ['facture_id' => 9, 'description' => 'Consultation urgence', 'quantite' => 1, 'prix_unitaire' => 20000, 'total' => 20000],
            ['facture_id' => 9, 'description' => 'Radiographie', 'quantite' => 2, 'prix_unitaire' => 20000, 'total' => 40000],
            ['facture_id' => 9, 'description' => 'Injection', 'quantite' => 2, 'prix_unitaire' => 2000, 'total' => 4000],
            ['facture_id' => 9, 'description' => 'Pose de plâtre', 'quantite' => 1, 'prix_unitaire' => 25000, 'total' => 25000],
            // Facture 10
            ['facture_id' => 10, 'description' => 'Consultation urgence', 'quantite' => 1, 'prix_unitaire' => 20000, 'total' => 20000],
            ['facture_id' => 10, 'description' => 'Test paludisme (TDR)', 'quantite' => 1, 'prix_unitaire' => 5000, 'total' => 5000],
            ['facture_id' => 10, 'description' => 'Bilan sanguin complet', 'quantite' => 1, 'prix_unitaire' => 25000, 'total' => 25000],
            ['facture_id' => 10, 'description' => 'Perfusion', 'quantite' => 1, 'prix_unitaire' => 5000, 'total' => 5000],
        ];

        foreach ($lignes as $ligne) {
            FactureLigne::create($ligne);
        }
    }
}
