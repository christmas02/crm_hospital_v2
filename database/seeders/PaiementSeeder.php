<?php

namespace Database\Seeders;

use App\Models\Paiement;
use App\Models\Facture;
use Illuminate\Database\Seeder;

class PaiementSeeder extends Seeder
{
    public function run()
    {
        $recuNum = 1;

        $factures = Facture::where('montant_paye', '>', 0)->get();

        foreach ($factures as $facture) {
            Paiement::create([
                'numero_recu' => 'REC-' . date('Ymd') . '-' . str_pad($recuNum++, 4, '0', STR_PAD_LEFT),
                'patient_id' => $facture->patient_id,
                'facture_id' => $facture->id,
                'date_paiement' => $facture->date_paiement ?? $facture->date,
                'montant' => $facture->montant_paye,
                'type' => 'consultation',
                'description' => 'Paiement facture ' . $facture->numero,
                'mode_paiement' => $facture->mode_paiement ?? 'especes',
                'reference' => $facture->mode_paiement === 'carte' ? 'CB-' . rand(100000, 999999) : ($facture->mode_paiement === 'mobile_money' ? 'MM-' . rand(100000, 999999) : null),
                'statut' => 'paye',
                'encaisse_par' => 1,
            ]);
        }
    }
}
