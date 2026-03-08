<?php

namespace Database\Seeders;

use App\Models\Paiement;
use Illuminate\Database\Seeder;

class PaiementSeeder extends Seeder
{
    public function run()
    {
        $paiements = [
            ['id' => 1, 'patient_id' => 1, 'facture_id' => 1, 'date_paiement' => '2024-02-20 09:30:00', 'montant' => 15000, 'type' => 'consultation', 'description' => 'Consultation Dr. Yao', 'mode_paiement' => 'especes', 'statut' => 'paye'],
            ['id' => 2, 'patient_id' => 1, 'facture_id' => null, 'date_paiement' => '2024-02-20 10:15:00', 'montant' => 7500, 'type' => 'medicaments', 'description' => 'Antipaludéen + Paracétamol', 'mode_paiement' => 'mobile_money', 'statut' => 'paye'],
            ['id' => 3, 'patient_id' => 2, 'facture_id' => 2, 'date_paiement' => '2024-02-20 11:00:00', 'montant' => 30000, 'type' => 'consultation', 'description' => 'Consultation cardiologie', 'mode_paiement' => 'carte', 'statut' => 'paye'],
            ['id' => 4, 'patient_id' => 3, 'facture_id' => 3, 'date_paiement' => '2024-02-20 14:30:00', 'montant' => 25000, 'type' => 'consultation', 'description' => 'Consultation prénatale', 'mode_paiement' => 'especes', 'statut' => 'paye'],
            ['id' => 5, 'patient_id' => 3, 'facture_id' => null, 'date_paiement' => '2024-02-18 16:00:00', 'montant' => 50000, 'type' => 'hospitalisation', 'description' => 'Chambre 101 - 1 jour', 'mode_paiement' => 'mobile_money', 'statut' => 'paye'],
            ['id' => 6, 'patient_id' => 6, 'facture_id' => 6, 'date_paiement' => '2024-02-15 10:00:00', 'montant' => 175000, 'type' => 'hospitalisation', 'description' => 'Chambre 103 - 5 jours', 'mode_paiement' => null, 'statut' => 'en_attente'],
            ['id' => 7, 'patient_id' => 10, 'facture_id' => 7, 'date_paiement' => '2024-02-19 08:30:00', 'montant' => 250000, 'type' => 'chirurgie', 'description' => 'Appendicectomie', 'mode_paiement' => null, 'statut' => 'en_attente'],
            ['id' => 8, 'patient_id' => 4, 'facture_id' => 4, 'date_paiement' => '2024-02-20 15:45:00', 'montant' => 15000, 'type' => 'consultation', 'description' => 'Contrôle diabète', 'mode_paiement' => 'especes', 'statut' => 'paye'],
        ];

        foreach ($paiements as $paiement) {
            Paiement::create($paiement);
        }
    }
}
