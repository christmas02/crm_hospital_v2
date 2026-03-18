<?php

namespace Database\Seeders;

use App\Models\FicheTraitement;
use App\Models\Consultation;
use App\Models\ActeMedical;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FicheTraitementSeeder extends Seeder
{
    public function run()
    {
        $consultations = Consultation::where('statut', 'termine')->with(['patient', 'medecin'])->limit(10)->get();
        $actes = ActeMedical::all();

        if ($consultations->isEmpty() || $actes->isEmpty()) return;

        $observations = [
            'Patient examiné. Signes vitaux normaux. Traitement symptomatique prescrit.',
            'Fièvre 39.2°C, céphalées. TDR positif. Traitement antipaludéen initié.',
            'Douleurs thoraciques atypiques. ECG normal. TA 150/95. Traitement HTA ajusté.',
            'Bilan complet réalisé. Résultats dans les normes. Contrôle dans 6 mois.',
            'Toux productive depuis 5 jours. Auscultation: râles bronchiques. Antibiotiques prescrits.',
            'Douleurs abdominales épigastriques. IPP prescrit. Régime conseillé.',
            'Contrôle diabète. Glycémie 1.12g/L. HbA1c 6.8%. Continuer traitement.',
            'Éruption cutanée diffuse. Dermocorticoïdes prescrits. Éviction allergène.',
            'Lombalgie mécanique. Anti-inflammatoires + repos. Kinésithérapie recommandée.',
            'Check-up annuel. Bilan normal. RAS. Prochain contrôle dans 1 an.',
        ];

        foreach ($consultations as $i => $consultation) {
            // Select 2-4 random actes
            $selectedActes = $actes->random(rand(2, 4));
            $total = $selectedActes->sum(fn($a) => $a->prix ?? 15000);

            $fiche = FicheTraitement::create([
                'consultation_id' => $consultation->id,
                'patient_id' => $consultation->patient_id,
                'medecin_id' => $consultation->medecin_id,
                'date' => $consultation->date,
                'observations' => $observations[$i % count($observations)],
                'total_facturable' => $total,
            ]);

            // Attach actes to fiche
            foreach ($selectedActes as $acte) {
                DB::table('fiche_traitement_actes')->insert([
                    'fiche_traitement_id' => $fiche->id,
                    'acte_medical_id' => $acte->id,
                    'nom' => $acte->nom,
                    'prix' => $acte->prix ?? 15000,
                    'quantite' => 1,
                    'facturable' => true,
                ]);
            }
        }
    }
}
