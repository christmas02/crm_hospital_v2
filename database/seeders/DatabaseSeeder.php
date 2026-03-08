<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // 1. Tables indépendantes
            MedecinSeeder::class,
            MedicamentSeeder::class,
            ActeMedicalSeeder::class,

            // 2. Patients (nécessaire pour chambres)
            PatientSeeder::class,

            // 3. Chambres (référence patients)
            ChambreSeeder::class,

            // 4. Dossiers médicaux
            DossierMedicalSeeder::class,

            // 5. Consultations
            ConsultationSeeder::class,

            // 6. Fiches de traitement et actes pivot
            FicheTraitementSeeder::class,

            // 7. Ordonnances et médicaments pivot
            OrdonnanceSeeder::class,
            PrescriptionSeeder::class,

            // 8. Factures et lignes
            FactureSeeder::class,

            // 9. Paiements et transactions
            PaiementSeeder::class,
            TransactionSeeder::class,

            // 10. Planning médecins
            PlanningSeeder::class,

            // 11. Rendez-vous et file d'attente
            RendezvousSeeder::class,
            FileAttenteSeeder::class,

            // 12. Hospitalisations
            HospitalisationSeeder::class,

            // 13. Pharmacie - approvisionnement et stock
            FicheApprovisionnementSeeder::class,
            MouvementStockSeeder::class,

            // 14. Utilisateurs de démo
            UserSeeder::class,
        ]);
    }
}
