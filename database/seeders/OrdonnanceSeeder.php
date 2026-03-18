<?php

namespace Database\Seeders;

use App\Models\Ordonnance;
use App\Models\OrdonnanceMedicament;
use App\Models\Consultation;
use App\Models\Medicament;
use Illuminate\Database\Seeder;

class OrdonnanceSeeder extends Seeder
{
    public function run()
    {
        $consultations = Consultation::where('statut', 'termine')->with(['patient', 'medecin'])->limit(8)->get();
        if ($consultations->isEmpty()) return;

        $statuts = ['en_attente', 'prepare', 'remis', 'remis', 'remis'];
        $recommandations = [
            'Repos et bonne hydratation. Revenir si fièvre persiste.',
            'Contrôle tension dans 1 mois. Régime hyposodé.',
            'Continuer suivi. Prochain RDV dans 2 semaines.',
            'Régime strict. Contrôle à jeun.',
            'Surveiller température. Consulter si aggravation.',
        ];

        $medicamentsParOrdonnance = [
            [
                ['nom' => 'Arthémether-Luméfantrine', 'posologie' => '2 cp 2x/jour', 'duree' => '3 jours', 'quantite' => 12],
                ['nom' => 'Paracétamol 1g', 'posologie' => '1 cp 3x/jour si fièvre', 'duree' => '5 jours', 'quantite' => 15],
            ],
            [
                ['nom' => 'Amlodipine 5mg', 'posologie' => '1 cp le matin', 'duree' => '30 jours', 'quantite' => 30],
                ['nom' => 'Aspirine 100mg', 'posologie' => '1 cp le soir', 'duree' => '30 jours', 'quantite' => 30],
            ],
            [
                ['nom' => 'Fer + Acide folique', 'posologie' => '1 cp/jour', 'duree' => '30 jours', 'quantite' => 30],
                ['nom' => 'Calcium Vitamine D', 'posologie' => '1 cp/jour', 'duree' => '30 jours', 'quantite' => 30],
            ],
            [
                ['nom' => 'Metformine 1000mg', 'posologie' => '1 cp 2x/jour', 'duree' => '30 jours', 'quantite' => 60],
                ['nom' => 'Glibenclamide 5mg', 'posologie' => '1 cp le matin', 'duree' => '30 jours', 'quantite' => 30],
            ],
            [
                ['nom' => 'Paracétamol 500mg', 'posologie' => '1 cp si fièvre/douleur', 'duree' => '3 jours', 'quantite' => 6],
            ],
            [
                ['nom' => 'Amoxicilline 250mg/5ml', 'posologie' => '5ml 3x/jour', 'duree' => '7 jours', 'quantite' => 1],
                ['nom' => 'Paracétamol sirop', 'posologie' => '5ml si fièvre', 'duree' => '5 jours', 'quantite' => 1],
            ],
            [
                ['nom' => 'Diclofénac 50mg', 'posologie' => '1 cp 2x/jour après repas', 'duree' => '5 jours', 'quantite' => 10],
                ['nom' => 'Oméprazole 20mg', 'posologie' => '1 cp le matin à jeun', 'duree' => '5 jours', 'quantite' => 5],
            ],
            [
                ['nom' => 'Bisoprolol 5mg', 'posologie' => '1 cp le matin', 'duree' => '30 jours', 'quantite' => 30],
                ['nom' => 'Amlodipine 10mg', 'posologie' => '1 cp le soir', 'duree' => '30 jours', 'quantite' => 30],
            ],
        ];

        foreach ($consultations as $i => $consultation) {
            $statut = $statuts[$i % count($statuts)];
            $ordonnance = Ordonnance::create([
                'consultation_id' => $consultation->id,
                'patient_id' => $consultation->patient_id,
                'medecin_id' => $consultation->medecin_id,
                'date' => $consultation->date,
                'numero_retrait' => 'RET-' . date('Y') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'statut_dispensation' => $statut,
                'date_preparation' => $statut !== 'en_attente' ? $consultation->date : null,
                'date_remise' => $statut === 'remis' ? $consultation->date : null,
                'remis_a' => $statut === 'remis' ? 'Infirmier' : null,
                'recommandations' => $recommandations[$i % count($recommandations)],
            ]);

            // Add medicaments for this ordonnance
            $meds = $medicamentsParOrdonnance[$i % count($medicamentsParOrdonnance)];
            foreach ($meds as $med) {
                OrdonnanceMedicament::create([
                    'ordonnance_id' => $ordonnance->id,
                    'nom' => $med['nom'],
                    'posologie' => $med['posologie'],
                    'duree' => $med['duree'],
                    'quantite' => $med['quantite'],
                ]);
            }
        }
    }
}
