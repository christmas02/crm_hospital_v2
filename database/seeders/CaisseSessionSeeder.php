<?php

namespace Database\Seeders;

use App\Models\CaisseSession;
use Illuminate\Database\Seeder;

class CaisseSessionSeeder extends Seeder
{
    public function run()
    {
        // Create sessions for the last 10 days
        for ($i = 10; $i >= 1; $i--) {
            $date = now()->subDays($i);
            $soldeOuverture = rand(50000, 200000);
            $encaissements = rand(100000, 500000);
            $depenses = rand(20000, 150000);
            $soldeAttendu = $soldeOuverture + $encaissements - $depenses;
            $ecart = [0, 0, 0, 0, rand(-5000, 5000)][rand(0, 4)]; // Most sessions have no ecart

            CaisseSession::create([
                'user_id' => 1,
                'ouverture' => $date->copy()->setTime(7, 30),
                'fermeture' => $date->copy()->setTime(17, 0),
                'solde_ouverture' => $soldeOuverture,
                'solde_fermeture' => $soldeAttendu + $ecart,
                'total_encaissements' => $encaissements,
                'total_depenses' => $depenses,
                'notes_ouverture' => $i === 1 ? 'Reprise après inventaire' : null,
                'notes_fermeture' => $ecart !== 0 ? 'Écart constaté de ' . number_format($ecart, 0, ',', ' ') . ' F' : null,
                'statut' => 'fermee',
            ]);
        }
    }
}
