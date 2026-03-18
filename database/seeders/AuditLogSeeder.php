<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) return;

        $logs = [
            // Connexions
            ['action' => 'login', 'description' => 'Connexion au système', 'model_type' => 'User', 'days_ago' => 0],
            ['action' => 'login', 'description' => 'Connexion au système', 'model_type' => 'User', 'days_ago' => 0],
            ['action' => 'login', 'description' => 'Connexion au système', 'model_type' => 'User', 'days_ago' => 1],
            ['action' => 'login', 'description' => 'Connexion au système', 'model_type' => 'User', 'days_ago' => 1],
            ['action' => 'login', 'description' => 'Connexion au système', 'model_type' => 'User', 'days_ago' => 2],

            // Patients
            ['action' => 'create', 'description' => 'Patient créé: Amadou Touré', 'model_type' => 'Patient', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Patient créé: Fatou Diallo', 'model_type' => 'Patient', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Patient créé: Ibrahim Koné', 'model_type' => 'Patient', 'days_ago' => 1],
            ['action' => 'update', 'description' => 'Patient modifié: Amadou Touré — téléphone mis à jour', 'model_type' => 'Patient', 'days_ago' => 0],
            ['action' => 'update', 'description' => 'Patient modifié: Rokia Doumbia — adresse mise à jour', 'model_type' => 'Patient', 'days_ago' => 1],
            ['action' => 'delete', 'description' => 'Patient supprimé: Test Dupont', 'model_type' => 'Patient', 'days_ago' => 3],

            // Consultations
            ['action' => 'create', 'description' => 'Consultation créée pour Amadou Touré — Dr. Yao', 'model_type' => 'Consultation', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Consultation créée pour Fatou Diallo — Dr. Touré', 'model_type' => 'Consultation', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Consultation créée pour Bintou Aka — Dr. Konaté', 'model_type' => 'Consultation', 'days_ago' => 1],
            ['action' => 'update', 'description' => 'Consultation terminée — Patient: Amadou Touré', 'model_type' => 'Consultation', 'days_ago' => 0],
            ['action' => 'update', 'description' => 'Consultation terminée — Patient: Ibrahim Koné', 'model_type' => 'Consultation', 'days_ago' => 1],

            // Médecins
            ['action' => 'create', 'description' => 'Médecin ajouté: Dr. Aminata Camara (Dermatologie)', 'model_type' => 'Medecin', 'days_ago' => 5],
            ['action' => 'update', 'description' => 'Statut médecin: Dr. Konaté → en_consultation', 'model_type' => 'Medecin', 'days_ago' => 0],
            ['action' => 'update', 'description' => 'Photo médecin mise à jour: Dr. Yao', 'model_type' => 'Medecin', 'days_ago' => 2],

            // Caisse
            ['action' => 'create', 'description' => 'Session de caisse ouverte — Solde: 150 000 F', 'model_type' => 'CaisseSession', 'days_ago' => 0],
            ['action' => 'update', 'description' => 'Session de caisse fermée — Solde: 385 000 F', 'model_type' => 'CaisseSession', 'days_ago' => 1],
            ['action' => 'update', 'description' => 'Encaissement 55 000 F sur facture FAC-2026-00003', 'model_type' => 'Facture', 'days_ago' => 0],
            ['action' => 'update', 'description' => 'Encaissement 25 000 F sur facture FAC-2026-00007', 'model_type' => 'Facture', 'days_ago' => 0],
            ['action' => 'update', 'description' => 'Encaissement 70 000 F sur facture FAC-2026-00001', 'model_type' => 'Facture', 'days_ago' => 1],
            ['action' => 'create', 'description' => 'Dépense enregistrée: Achat fournitures bureau — 45 000 F', 'model_type' => 'Transaction', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Dépense enregistrée: Produits d\'entretien — 28 000 F', 'model_type' => 'Transaction', 'days_ago' => 1],
            ['action' => 'create', 'description' => 'Avoir AV-20260317-0001 créé: 15 000 F', 'model_type' => 'Facture', 'days_ago' => 2],
            ['action' => 'create', 'description' => 'Remboursement RMB-20260316-0001 de 20 000 F', 'model_type' => 'Facture', 'days_ago' => 2],

            // Pharmacie
            ['action' => 'create', 'description' => 'Mouvement stock: entrée de 100 unités — Paracétamol 500mg', 'model_type' => 'MouvementStock', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Mouvement stock: sortie de 20 unités — Amoxicilline 500mg', 'model_type' => 'MouvementStock', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Mouvement stock: entrée de 50 unités — Diclofénac 50mg', 'model_type' => 'MouvementStock', 'days_ago' => 1],
            ['action' => 'create', 'description' => 'Nouveau médicament ajouté: Métronidazole 250mg', 'model_type' => 'Medicament', 'days_ago' => 4],

            // Hospitalisation
            ['action' => 'create', 'description' => 'Admission patient Seydou Coulibaly en chambre 101', 'model_type' => 'Hospitalisation', 'days_ago' => 1],
            ['action' => 'update', 'description' => 'Sortie patient Bintou Aka — Chambre 201 libérée', 'model_type' => 'Hospitalisation', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Chambre 305 créée (VIP, Étage 3)', 'model_type' => 'Chambre', 'days_ago' => 5],

            // Personnel
            ['action' => 'create', 'description' => 'Personnel ajouté: Mariam Koné (EMP-0001) — Infirmière chef', 'model_type' => 'Personnel', 'days_ago' => 7],
            ['action' => 'create', 'description' => 'Personnel ajouté: Mamadou Diallo (EMP-0002) — Infirmier', 'model_type' => 'Personnel', 'days_ago' => 7],
            ['action' => 'update', 'description' => 'Personnel modifié: Fatou Touré — poste mis à jour', 'model_type' => 'Personnel', 'days_ago' => 3],

            // Laboratoire
            ['action' => 'create', 'description' => 'Demande labo LAB-20260318-0001 créée — NFS + Glycémie', 'model_type' => 'DemandeLabo', 'days_ago' => 0],
            ['action' => 'update', 'description' => 'Résultats labo saisis — LAB-20260317-0003', 'model_type' => 'DemandeLabo', 'days_ago' => 1],

            // Certificats
            ['action' => 'create', 'description' => 'Certificat CERT-20260318-0001 — Arrêt maladie 3 jours', 'model_type' => 'CertificatMedical', 'days_ago' => 0],
            ['action' => 'create', 'description' => 'Certificat CERT-20260317-0002 — Aptitude au travail', 'model_type' => 'CertificatMedical', 'days_ago' => 1],

            // Références
            ['action' => 'create', 'description' => 'Référence REF-20260318-0001 — vers Dr. Konaté (Cardiologie)', 'model_type' => 'Reference', 'days_ago' => 0],

            // Rappels
            ['action' => 'create', 'description' => 'Rappel envoyé à Amadou Touré pour le 19/03/2026 à 09:00', 'model_type' => 'Consultation', 'days_ago' => 0],
            ['action' => 'create', 'description' => '6 rappels envoyés sur 8 rendez-vous', 'model_type' => null, 'days_ago' => 1],
        ];

        $ips = ['192.168.1.10', '192.168.1.15', '192.168.1.20', '10.0.0.5', '10.0.0.12'];

        foreach ($logs as $log) {
            $user = $users->random();
            $modelId = $log['model_type'] ? rand(1, 20) : null;

            AuditLog::create([
                'user_id' => $user->id,
                'action' => $log['action'],
                'model_type' => $log['model_type'],
                'model_id' => $modelId,
                'description' => $log['description'],
                'changes' => null,
                'ip_address' => $ips[array_rand($ips)],
                'created_at' => now()->subDays($log['days_ago'])->subMinutes(rand(0, 480)),
                'updated_at' => now()->subDays($log['days_ago'])->subMinutes(rand(0, 480)),
            ]);
        }
    }
}
