<?php

namespace Database\Seeders;

use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();
        $consultations = Consultation::where('statut', 'termine')->get();

        $actes = [
            ['Consultation générale', 15000],
            ['Consultation spécialisée', 25000],
            ['Radiographie', 20000],
            ['Échographie', 35000],
            ['Analyse sanguine', 12000],
            ['Bilan complet', 45000],
            ['Soins infirmiers', 8000],
            ['Injection', 5000],
            ['Pansement', 3000],
            ['Hospitalisation (journée)', 50000],
            ['Chirurgie mineure', 150000],
            ['Accouchement normal', 200000],
        ];

        $numero = 1;

        foreach ($patients->take(20) as $i => $patient) {
            $consultation = $consultations->where('patient_id', $patient->id)->first();
            $dateBase = now()->subDays(rand(1, 120));

            // Choose 1-3 random actes
            $selectedActes = collect($actes)->random(rand(1, 3));
            $sousTotal = $selectedActes->sum(fn($a) => $a[1]);

            // Varied statuses
            $statuts = ['payee', 'payee', 'payee', 'en_attente', 'en_attente', 'envoyee', 'annulee'];
            $statut = $statuts[$i % count($statuts)];

            // Some with insurance
            $hasPriseEnCharge = $i % 5 === 0;
            $tauxCouverture = $hasPriseEnCharge ? [50, 70, 80, 100][rand(0, 3)] : 0;
            $montantCouvert = (int)round($sousTotal * $tauxCouverture / 100);
            $montantPatient = $sousTotal - $montantCouvert;

            // Some with discount
            $remise = $i % 7 === 0 ? (int)round($sousTotal * 0.1) : 0;
            $montantNet = $sousTotal - $remise;

            // Payment amounts
            $montantPaye = 0;
            $modePaiement = null;
            $datePaiement = null;

            if ($statut === 'payee') {
                $montantPaye = $hasPriseEnCharge ? $montantPatient : $montantNet;
                $modePaiement = ['especes', 'carte', 'mobile_money', 'cheque', 'virement'][rand(0, 4)];
                $datePaiement = $dateBase->copy()->addDays(rand(0, 5));
            } elseif ($i % 8 === 0 && $statut === 'en_attente') {
                // Partial payment
                $montantPaye = (int)round(($hasPriseEnCharge ? $montantPatient : $montantNet) * 0.4);
                $modePaiement = 'especes';
            }

            $montantRestant = ($hasPriseEnCharge ? $montantPatient : $montantNet) - $montantPaye;

            $facture = Facture::create([
                'numero' => 'FAC-' . date('Y') . '-' . str_pad($numero++, 5, '0', STR_PAD_LEFT),
                'patient_id' => $patient->id,
                'consultation_id' => $consultation?->id,
                'date' => $dateBase,
                'montant' => $sousTotal,
                'montant_remise' => $remise,
                'montant_tva' => 0,
                'montant_net' => $montantNet,
                'montant_paye' => $montantPaye,
                'montant_restant' => max(0, $montantRestant),
                'statut' => $statut,
                'mode_paiement' => $modePaiement,
                'date_paiement' => $datePaiement,
                'type_prise_en_charge' => $hasPriseEnCharge ? ['assurance', 'mutuelle', 'indigent'][rand(0, 2)] : null,
                'organisme_prise_en_charge' => $hasPriseEnCharge ? ['CNAM', 'MUGEF-CI', 'CMU', 'AXA Santé'][rand(0, 3)] : null,
                'numero_assurance' => $hasPriseEnCharge ? 'ASS-' . rand(100000, 999999) : null,
                'taux_couverture' => $tauxCouverture,
                'montant_couvert' => $montantCouvert,
                'montant_patient' => $montantPatient,
            ]);

            // Create lignes
            foreach ($selectedActes as $acte) {
                FactureLigne::create([
                    'facture_id' => $facture->id,
                    'description' => $acte[0],
                    'quantite' => 1,
                    'prix_unitaire' => $acte[1],
                    'total' => $acte[1],
                ]);
            }
        }
    }
}
