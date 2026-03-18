<?php

namespace Database\Seeders;

use App\Models\Hospitalisation;
use App\Models\Patient;
use App\Models\Chambre;
use App\Models\Medecin;
use Illuminate\Database\Seeder;

class HospitalisationSeeder extends Seeder
{
    public function run()
    {
        $chambresLibres = Chambre::where('statut', 'libre')->get();
        $patients = Patient::where('statut', 'actif')->get();
        $medecins = Medecin::all();
        $motifs = ['Pneumonie sévère', 'Fracture nécessitant surveillance', 'Crise d\'asthme', 'Appendicite', 'Complications diabétiques'];

        if ($chambresLibres->isEmpty() || $patients->isEmpty()) return;

        // Create 2-3 active hospitalisations
        foreach ($chambresLibres->take(min(3, $chambresLibres->count())) as $i => $chambre) {
            $patient = $patients->random();

            Hospitalisation::create([
                'patient_id' => $patient->id,
                'chambre_id' => $chambre->id,
                'medecin_id' => $medecins->random()->id,
                'date_admission' => now()->subDays(rand(1, 7)),
                'motif' => $motifs[$i % count($motifs)],
                'statut' => 'en_cours',
            ]);

            $chambre->update(['statut' => 'occupee', 'patient_id' => $patient->id]);
            $patient->update(['statut' => 'hospitalise']);
        }
    }
}
