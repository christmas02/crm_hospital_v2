<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\Paiement;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Create entrees from paiements
        $paiements = Paiement::where('statut', 'paye')->get();
        foreach ($paiements as $paiement) {
            Transaction::create([
                'date' => $paiement->date_paiement,
                'type' => 'entree',
                'montant' => $paiement->montant,
                'description' => 'Encaissement ' . $paiement->description,
                'categorie' => 'consultation',
            ]);
        }

        // Create sorties (expenses) over 60 days
        $depenses = [
            ['Achat de fournitures bureau', 'fournitures', 15000, 45000],
            ['Produits d\'entretien', 'maintenance', 25000, 50000],
            ['Achat médicaments pharmacie', 'pharmacie', 50000, 200000],
            ['Réparation climatisation', 'maintenance', 30000, 80000],
            ['Fournitures médicales', 'fournitures', 20000, 100000],
            ['Eau et électricité', 'maintenance', 40000, 60000],
            ['Achat de gants et masques', 'pharmacie', 10000, 30000],
            ['Transport analyses laboratoire', 'autre', 5000, 15000],
            ['Maintenance équipements', 'maintenance', 50000, 150000],
            ['Impression documents', 'fournitures', 8000, 20000],
        ];

        for ($i = 0; $i < 25; $i++) {
            $dep = $depenses[array_rand($depenses)];
            Transaction::create([
                'date' => now()->subDays(rand(0, 60)),
                'type' => 'sortie',
                'montant' => rand($dep[2], $dep[3]),
                'description' => $dep[0],
                'categorie' => $dep[1],
            ]);
        }
    }
}
