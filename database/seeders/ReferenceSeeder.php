<?php

namespace Database\Seeders;

use App\Models\Reference;
use App\Models\Consultation;
use App\Models\Medecin;
use Illuminate\Database\Seeder;

class ReferenceSeeder extends Seeder
{
    public function run()
    {
        $consultations = Consultation::where('statut', 'termine')->with(['patient', 'medecin'])->limit(5)->get();
        $medecins = Medecin::all();
        $motifs = ['Avis spécialisé cardiologie', 'Suspicion de pathologie dermatologique', 'Bilan approfondi', 'Suivi spécialisé recommandé', 'Examen complémentaire nécessaire'];
        $urgences = ['normal', 'normal', 'urgent'];
        $statuts = ['en_attente', 'en_attente', 'acceptee', 'consultation_faite'];

        $num = 1;
        foreach ($consultations as $consultation) {
            $medecinCible = $medecins->where('id', '!=', $consultation->medecin_id)->random();
            $statut = $statuts[array_rand($statuts)];

            Reference::create([
                'numero' => 'REF-' . $consultation->date->format('Ymd') . '-' . str_pad($num++, 4, '0', STR_PAD_LEFT),
                'patient_id' => $consultation->patient_id,
                'medecin_referent_id' => $consultation->medecin_id,
                'medecin_cible_id' => $medecinCible->id,
                'consultation_id' => $consultation->id,
                'date_reference' => $consultation->date,
                'motif' => $motifs[array_rand($motifs)],
                'contexte_clinique' => 'Patient présentant des symptômes nécessitant un avis spécialisé.',
                'urgence' => $urgences[array_rand($urgences)],
                'statut' => $statut,
                'reponse_specialiste' => $statut === 'consultation_faite' ? 'Patient vu. Diagnostic confirmé, traitement ajusté.' : null,
                'date_consultation_specialiste' => $statut === 'consultation_faite' ? $consultation->date->copy()->addDays(rand(3, 10)) : null,
            ]);
        }
    }
}
