<?php

namespace Database\Seeders;

use App\Models\DemandeLabo;
use App\Models\ResultatLabo;
use App\Models\ExamenLabo;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class DemandeLaboSeeder extends Seeder
{
    public function run()
    {
        $consultations = Consultation::where('statut', 'termine')->with(['patient', 'medecin'])->limit(10)->get();
        $examens = ExamenLabo::all();
        $statuts = ['termine', 'termine', 'termine', 'en_attente', 'en_cours', 'preleve'];
        $urgences = ['normal', 'normal', 'normal', 'urgent', 'tres_urgent'];

        $num = 1;
        foreach ($consultations as $consultation) {
            $statut = $statuts[array_rand($statuts)];
            $demande = DemandeLabo::create([
                'numero' => 'LAB-' . $consultation->date->format('Ymd') . '-' . str_pad($num++, 4, '0', STR_PAD_LEFT),
                'patient_id' => $consultation->patient_id,
                'medecin_id' => $consultation->medecin_id,
                'consultation_id' => $consultation->id,
                'date_demande' => $consultation->date,
                'statut' => $statut,
                'urgence' => $urgences[array_rand($urgences)],
                'notes_cliniques' => 'Bilan demandé suite à la consultation.',
                'date_resultat' => $statut === 'termine' ? $consultation->date->copy()->addDays(rand(1, 3)) : null,
                'realise_par' => $statut === 'termine' ? 1 : null,
            ]);

            // Add 2-5 random exams to each demand
            $nbExamens = min(rand(2, 5), $examens->count());
            $selectedExamens = $examens->random($nbExamens);
            foreach ($selectedExamens as $examen) {
                $interpretation = null;
                $valeur = null;

                if ($statut === 'termine') {
                    $interpretations = ['normal', 'normal', 'normal', 'normal', 'eleve', 'bas', 'critique'];
                    $interpretation = $interpretations[array_rand($interpretations)];
                    $valeur = $examen->valeur_normale ? (string) rand(1, 100) : 'Négatif';
                }

                ResultatLabo::create([
                    'demande_labo_id' => $demande->id,
                    'examen_labo_id' => $examen->id,
                    'valeur' => $valeur,
                    'unite' => $examen->unite,
                    'valeur_reference' => $examen->valeur_normale,
                    'interpretation' => $interpretation,
                    'commentaire' => $interpretation === 'critique' ? 'Valeur critique - contacter le médecin' : null,
                ]);
            }
        }
    }
}
