<?php

namespace Database\Seeders;

use App\Models\Medicament;
use Illuminate\Database\Seeder;

class MedicamentSeeder extends Seeder
{
    public function run()
    {
        $medicaments = [
            ['id' => 1, 'nom' => 'Paracétamol 500mg', 'categorie' => 'Antalgique', 'forme' => 'Comprimé', 'stock' => 500, 'stock_min' => 100, 'prix_unitaire' => 50, 'fournisseur' => 'Pharma CI'],
            ['id' => 2, 'nom' => 'Paracétamol 1g', 'categorie' => 'Antalgique', 'forme' => 'Comprimé', 'stock' => 350, 'stock_min' => 100, 'prix_unitaire' => 75, 'fournisseur' => 'Pharma CI'],
            ['id' => 3, 'nom' => 'Amoxicilline 500mg', 'categorie' => 'Antibiotique', 'forme' => 'Gélule', 'stock' => 200, 'stock_min' => 50, 'prix_unitaire' => 150, 'fournisseur' => 'MedAfrique'],
            ['id' => 4, 'nom' => 'Arthémether-Luméfantrine', 'categorie' => 'Antipaludéen', 'forme' => 'Comprimé', 'stock' => 45, 'stock_min' => 50, 'prix_unitaire' => 2500, 'fournisseur' => 'Novartis'],
            ['id' => 5, 'nom' => 'Amlodipine 5mg', 'categorie' => 'Antihypertenseur', 'forme' => 'Comprimé', 'stock' => 180, 'stock_min' => 40, 'prix_unitaire' => 200, 'fournisseur' => 'Sanofi'],
            ['id' => 6, 'nom' => 'Metformine 500mg', 'categorie' => 'Antidiabétique', 'forme' => 'Comprimé', 'stock' => 300, 'stock_min' => 60, 'prix_unitaire' => 100, 'fournisseur' => 'MedAfrique'],
            ['id' => 7, 'nom' => 'Oméprazole 20mg', 'categorie' => 'Anti-ulcéreux', 'forme' => 'Gélule', 'stock' => 150, 'stock_min' => 30, 'prix_unitaire' => 250, 'fournisseur' => 'Pharma CI'],
            ['id' => 8, 'nom' => 'Fer + Acide folique', 'categorie' => 'Supplément', 'forme' => 'Comprimé', 'stock' => 400, 'stock_min' => 100, 'prix_unitaire' => 75, 'fournisseur' => 'Pharma CI'],
            ['id' => 9, 'nom' => 'Diclofénac 50mg', 'categorie' => 'Anti-inflammatoire', 'forme' => 'Comprimé', 'stock' => 25, 'stock_min' => 50, 'prix_unitaire' => 100, 'fournisseur' => 'MedAfrique'],
            ['id' => 10, 'nom' => 'Sérum salé 0.9%', 'categorie' => 'Perfusion', 'forme' => 'Flacon 500ml', 'stock' => 80, 'stock_min' => 20, 'prix_unitaire' => 1500, 'fournisseur' => 'B.Braun'],
            ['id' => 11, 'nom' => 'Insuline Rapide', 'categorie' => 'Antidiabétique', 'forme' => 'Flacon', 'stock' => 15, 'stock_min' => 10, 'prix_unitaire' => 8000, 'fournisseur' => 'Novo Nordisk'],
            ['id' => 12, 'nom' => 'Aspirine 100mg', 'categorie' => 'Antiagrégant', 'forme' => 'Comprimé', 'stock' => 250, 'stock_min' => 50, 'prix_unitaire' => 50, 'fournisseur' => 'Bayer'],
        ];

        foreach ($medicaments as $medicament) {
            Medicament::create($medicament);
        }
    }
}
