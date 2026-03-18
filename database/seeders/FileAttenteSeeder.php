<?php

namespace Database\Seeders;

use App\Models\FileAttente;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class FileAttenteSeeder extends Seeder
{
    public function run()
    {
        $consultationsEnAttente = Consultation::where('statut', 'en_attente')
            ->whereDate('date', today())
            ->with(['patient', 'medecin'])
            ->orderBy('heure')
            ->get();

        $position = [];
        foreach ($consultationsEnAttente as $consultation) {
            $medId = $consultation->medecin_id;
            if (!isset($position[$medId])) $position[$medId] = 1;

            FileAttente::create([
                'consultation_id' => $consultation->id,
                'patient_id' => $consultation->patient_id,
                'medecin_id' => $medId,
                'heure_arrivee' => $consultation->heure,
                'position' => $position[$medId]++,
                'statut' => 'en_attente',
            ]);
        }
    }
}
