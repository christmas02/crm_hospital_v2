<?php

namespace Database\Seeders;

use App\Models\ActeMedical;
use Illuminate\Database\Seeder;

class ActeMedicalSeeder extends Seeder
{
    public function run()
    {
        $actes = [
            // Consultations
            ['id' => 1, 'code' => 'CONS-GEN', 'nom' => 'Consultation générale', 'categorie' => 'consultation', 'prix' => 15000, 'facturable' => true],
            ['id' => 2, 'code' => 'CONS-SPE', 'nom' => 'Consultation spécialisée', 'categorie' => 'consultation', 'prix' => 25000, 'facturable' => true],
            ['id' => 3, 'code' => 'CONS-URG', 'nom' => 'Consultation urgence', 'categorie' => 'consultation', 'prix' => 20000, 'facturable' => true],
            // Examens
            ['id' => 4, 'code' => 'EXA-SANG', 'nom' => 'Bilan sanguin complet', 'categorie' => 'examen', 'prix' => 25000, 'facturable' => true],
            ['id' => 5, 'code' => 'EXA-URIN', 'nom' => 'Analyse urinaire', 'categorie' => 'examen', 'prix' => 8000, 'facturable' => true],
            ['id' => 6, 'code' => 'EXA-ECG', 'nom' => 'Électrocardiogramme (ECG)', 'categorie' => 'examen', 'prix' => 15000, 'facturable' => true],
            ['id' => 7, 'code' => 'EXA-ECHO', 'nom' => 'Échographie', 'categorie' => 'examen', 'prix' => 35000, 'facturable' => true],
            ['id' => 8, 'code' => 'EXA-RADIO', 'nom' => 'Radiographie', 'categorie' => 'examen', 'prix' => 20000, 'facturable' => true],
            ['id' => 9, 'code' => 'EXA-SCAN', 'nom' => 'Scanner', 'categorie' => 'examen', 'prix' => 75000, 'facturable' => true],
            ['id' => 10, 'code' => 'EXA-PALU', 'nom' => 'Test paludisme (TDR)', 'categorie' => 'examen', 'prix' => 5000, 'facturable' => true],
            ['id' => 11, 'code' => 'EXA-GLYC', 'nom' => 'Glycémie', 'categorie' => 'examen', 'prix' => 3000, 'facturable' => true],
            ['id' => 12, 'code' => 'EXA-TA', 'nom' => 'Prise de tension', 'categorie' => 'examen', 'prix' => 0, 'facturable' => false],
            // Soins
            ['id' => 13, 'code' => 'SOIN-INJ', 'nom' => 'Injection', 'categorie' => 'soin', 'prix' => 2000, 'facturable' => true],
            ['id' => 14, 'code' => 'SOIN-PERF', 'nom' => 'Perfusion', 'categorie' => 'soin', 'prix' => 5000, 'facturable' => true],
            ['id' => 15, 'code' => 'SOIN-PANS', 'nom' => 'Pansement simple', 'categorie' => 'soin', 'prix' => 3000, 'facturable' => true],
            ['id' => 16, 'code' => 'SOIN-PANC', 'nom' => 'Pansement complexe', 'categorie' => 'soin', 'prix' => 8000, 'facturable' => true],
            ['id' => 17, 'code' => 'SOIN-SUTU', 'nom' => 'Suture', 'categorie' => 'soin', 'prix' => 15000, 'facturable' => true],
            ['id' => 18, 'code' => 'SOIN-VACC', 'nom' => 'Vaccination', 'categorie' => 'soin', 'prix' => 5000, 'facturable' => true],
            ['id' => 19, 'code' => 'SOIN-NEB', 'nom' => 'Nébulisation', 'categorie' => 'soin', 'prix' => 5000, 'facturable' => true],
            // Actes spécialisés
            ['id' => 20, 'code' => 'ACT-PLATR', 'nom' => 'Pose de plâtre', 'categorie' => 'acte', 'prix' => 25000, 'facturable' => true],
            ['id' => 21, 'code' => 'ACT-SOND', 'nom' => 'Pose sonde urinaire', 'categorie' => 'acte', 'prix' => 10000, 'facturable' => true],
            ['id' => 22, 'code' => 'ACT-ACCOU', 'nom' => 'Accouchement normal', 'categorie' => 'acte', 'prix' => 100000, 'facturable' => true],
        ];

        foreach ($actes as $acte) {
            ActeMedical::create($acte);
        }
    }
}
