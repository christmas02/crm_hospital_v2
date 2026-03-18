<?php

namespace Database\Seeders;

use App\Models\CertificatMedical;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class CertificatMedicalSeeder extends Seeder
{
    public function run()
    {
        $consultations = Consultation::where('statut', 'termine')->with(['patient', 'medecin'])->limit(8)->get();
        $types = ['arret_maladie', 'arret_maladie', 'aptitude', 'medical_general', 'arret_maladie'];
        $motifs = [
            'arret_maladie' => ['Grippe saisonnière', 'Lombalgie aiguë', 'Gastro-entérite', 'Paludisme', 'Fatigue intense'],
            'aptitude' => ['Aptitude au travail', 'Aptitude au sport', 'Aptitude à la conduite'],
            'medical_general' => ['Certificat médical pour inscription', 'Certificat de bonne santé'],
        ];

        $num = 1;
        foreach ($consultations as $consultation) {
            $type = $types[array_rand($types)];
            $nbJours = $type === 'arret_maladie' ? rand(2, 10) : null;
            $dateDebut = $type === 'arret_maladie' ? $consultation->date : null;
            $dateFin = $dateDebut ? $dateDebut->copy()->addDays($nbJours) : null;

            CertificatMedical::create([
                'numero' => 'CERT-' . $consultation->date->format('Ymd') . '-' . str_pad($num++, 4, '0', STR_PAD_LEFT),
                'patient_id' => $consultation->patient_id,
                'medecin_id' => $consultation->medecin_id,
                'consultation_id' => $consultation->id,
                'type' => $type,
                'date_emission' => $consultation->date,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'nb_jours' => $nbJours,
                'motif' => $motifs[$type][array_rand($motifs[$type])],
                'observations' => 'Patient examiné ce jour.',
                'conclusion' => $type === 'aptitude' ? 'Apte sans restriction' : null,
            ]);
        }
    }
}
