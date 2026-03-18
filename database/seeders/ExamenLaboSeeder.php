<?php

namespace Database\Seeders;

use App\Models\ExamenLabo;
use Illuminate\Database\Seeder;

class ExamenLaboSeeder extends Seeder
{
    public function run()
    {
        $examens = [
            ['nom' => 'Numeration Formule Sanguine (NFS)', 'categorie' => 'Hematologie', 'unite' => '', 'valeur_normale' => '', 'prix' => 5000],
            ['nom' => 'Hemoglobine', 'categorie' => 'Hematologie', 'unite' => 'g/dL', 'valeur_normale' => '12.0 - 17.0', 'prix' => 3000],
            ['nom' => 'Glycemie a jeun', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_normale' => '0.70 - 1.10', 'prix' => 3000],
            ['nom' => 'Creatinine', 'categorie' => 'Biochimie', 'unite' => 'mg/L', 'valeur_normale' => '6.0 - 13.0', 'prix' => 3500],
            ['nom' => 'Transaminases ALAT', 'categorie' => 'Biochimie', 'unite' => 'UI/L', 'valeur_normale' => '5 - 45', 'prix' => 4000],
            ['nom' => 'Transaminases ASAT', 'categorie' => 'Biochimie', 'unite' => 'UI/L', 'valeur_normale' => '5 - 40', 'prix' => 4000],
            ['nom' => 'Cholesterol total', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_normale' => '< 2.0', 'prix' => 4000],
            ['nom' => 'Triglycerides', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_normale' => '< 1.5', 'prix' => 4000],
            ['nom' => 'Acide urique', 'categorie' => 'Biochimie', 'unite' => 'mg/L', 'valeur_normale' => '25 - 70', 'prix' => 3500],
            ['nom' => 'Uree', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_normale' => '0.15 - 0.45', 'prix' => 3000],
            ['nom' => 'Groupe sanguin + Rhesus', 'categorie' => 'Immuno-hematologie', 'unite' => '', 'valeur_normale' => '', 'prix' => 5000],
            ['nom' => 'Vitesse de sedimentation (VS)', 'categorie' => 'Hematologie', 'unite' => 'mm/h', 'valeur_normale' => '< 20', 'prix' => 2500],
            ['nom' => 'CRP (Proteine C Reactive)', 'categorie' => 'Immunologie', 'unite' => 'mg/L', 'valeur_normale' => '< 6', 'prix' => 5000],
            ['nom' => 'ECBU (Examen urinaire)', 'categorie' => 'Bacteriologie', 'unite' => '', 'valeur_normale' => 'Sterile', 'prix' => 5000],
            ['nom' => 'Goutte epaisse (Paludisme)', 'categorie' => 'Parasitologie', 'unite' => '', 'valeur_normale' => 'Negatif', 'prix' => 3000],
            ['nom' => 'Test VIH', 'categorie' => 'Serologie', 'unite' => '', 'valeur_normale' => 'Negatif', 'prix' => 5000],
            ['nom' => 'Hepatite B (AgHBs)', 'categorie' => 'Serologie', 'unite' => '', 'valeur_normale' => 'Negatif', 'prix' => 5000],
            ['nom' => 'Hepatite C (Anti-HCV)', 'categorie' => 'Serologie', 'unite' => '', 'valeur_normale' => 'Negatif', 'prix' => 5000],
            ['nom' => 'Widal (Fievre typhoide)', 'categorie' => 'Serologie', 'unite' => '', 'valeur_normale' => 'Negatif', 'prix' => 4000],
            ['nom' => 'Test de grossesse (BetaHCG)', 'categorie' => 'Biochimie', 'unite' => '', 'valeur_normale' => '', 'prix' => 5000],
        ];

        foreach ($examens as $examen) {
            ExamenLabo::firstOrCreate(['nom' => $examen['nom']], $examen);
        }
    }
}
