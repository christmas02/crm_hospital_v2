<?php

namespace Database\Seeders;

use App\Models\Rendezvous;
use Illuminate\Database\Seeder;

class RendezvousSeeder extends Seeder
{
    public function run()
    {
        $patients = \App\Models\Patient::all();
        $medecins = \App\Models\Medecin::all();
        $motifs = ['Contrôle de routine', 'Suivi traitement', 'Résultats d\'analyses', 'Douleurs persistantes', 'Renouvellement ordonnance', 'Vaccination', 'Bilan annuel', 'Suivi post-opératoire'];
        $statuts = ['en_attente', 'en_attente', 'en_attente', 'confirme', 'confirme'];

        for ($i = 0; $i < 15; $i++) {
            Rendezvous::create([
                'patient_id' => $patients->random()->id,
                'medecin_id' => $medecins->random()->id,
                'date' => now()->addDays(rand(1, 14)),
                'heure' => sprintf('%02d:%02d', rand(8, 16), [0, 15, 30, 45][rand(0, 3)]),
                'motif' => $motifs[array_rand($motifs)],
                'statut' => $statuts[array_rand($statuts)],
            ]);
        }
    }
}
