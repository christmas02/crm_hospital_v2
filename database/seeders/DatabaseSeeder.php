<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // 1. Utilisateurs de démo (nécessaire pour les FK)
            UserSeeder::class,

            // 2. Tables indépendantes
            MedecinSeeder::class,
            MedicamentSeeder::class,
            ActeMedicalSeeder::class,

            // 3. Examens de laboratoire
            ExamenLaboSeeder::class,

            // 4. Patients
            PatientSeeder::class,

            // 5. Personnel
            PersonnelSeeder::class,

            // 6. Chambres
            ChambreSeeder::class,

            // 7. Dossiers médicaux
            DossierMedicalSeeder::class,

            // 8. Consultations
            ConsultationSeeder::class,

            // 9. Signes vitaux (après consultations)
            SigneVitalSeeder::class,

            // 10. Fiches de traitement et actes pivot
            FicheTraitementSeeder::class,

            // 11. Ordonnances et prescriptions
            OrdonnanceSeeder::class,
            PrescriptionSeeder::class,

            // 12. Certificats médicaux (après consultations)
            CertificatMedicalSeeder::class,

            // 13. Demandes de laboratoire (après consultations)
            DemandeLaboSeeder::class,

            // 14. Vaccinations
            VaccinationSeeder::class,

            // 15. Références (après consultations)
            ReferenceSeeder::class,

            // 16. Factures et lignes
            FactureSeeder::class,

            // 17. Paiements et transactions
            PaiementSeeder::class,
            TransactionSeeder::class,

            // 18. Sessions de caisse
            CaisseSessionSeeder::class,

            // 19. Planning médecins
            PlanningSeeder::class,

            // 20. Rendez-vous et file d'attente
            RendezvousSeeder::class,
            FileAttenteSeeder::class,

            // 21. Hospitalisations
            HospitalisationSeeder::class,

            // 22. Pharmacie - approvisionnement et stock
            FicheApprovisionnementSeeder::class,
            MouvementStockSeeder::class,

            // 23. Journal d'audit
            AuditLogSeeder::class,
        ]);
    }
}
