<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $transactions = [
            ['id' => 1, 'date' => '2024-02-20', 'type' => 'entree', 'montant' => 15000, 'description' => 'Paiement consultation #1', 'categorie' => 'consultation'],
            ['id' => 2, 'date' => '2024-02-20', 'type' => 'entree', 'montant' => 7500, 'description' => 'Vente médicaments', 'categorie' => 'pharmacie'],
            ['id' => 3, 'date' => '2024-02-20', 'type' => 'entree', 'montant' => 30000, 'description' => 'Paiement consultation #3', 'categorie' => 'consultation'],
            ['id' => 4, 'date' => '2024-02-20', 'type' => 'entree', 'montant' => 25000, 'description' => 'Paiement consultation #4', 'categorie' => 'consultation'],
            ['id' => 5, 'date' => '2024-02-20', 'type' => 'sortie', 'montant' => 15000, 'description' => 'Achat fournitures', 'categorie' => 'depense'],
            ['id' => 6, 'date' => '2024-02-20', 'type' => 'entree', 'montant' => 50000, 'description' => 'Hospitalisation #5', 'categorie' => 'hospitalisation'],
            ['id' => 7, 'date' => '2024-02-19', 'type' => 'entree', 'montant' => 45000, 'description' => 'Consultations diverses', 'categorie' => 'consultation'],
            ['id' => 8, 'date' => '2024-02-19', 'type' => 'sortie', 'montant' => 80000, 'description' => 'Commande médicaments', 'categorie' => 'pharmacie'],
            ['id' => 9, 'date' => '2024-02-18', 'type' => 'entree', 'montant' => 120000, 'description' => 'Paiements divers', 'categorie' => 'autre'],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
