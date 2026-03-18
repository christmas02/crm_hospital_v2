<?php

namespace Database\Seeders;

use App\Models\Personnel;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PersonnelSeeder extends Seeder
{
    public function run()
    {
        $staff = [
            ['nom' => 'Koné', 'prenom' => 'Mariam', 'sexe' => 'F', 'categorie' => 'infirmier', 'poste' => 'Infirmière chef', 'service' => 'Urgences', 'type_contrat' => 'CDI', 'salaire' => 350000],
            ['nom' => 'Diallo', 'prenom' => 'Mamadou', 'sexe' => 'M', 'categorie' => 'infirmier', 'poste' => 'Infirmier', 'service' => 'Médecine générale', 'type_contrat' => 'CDI', 'salaire' => 280000],
            ['nom' => 'Touré', 'prenom' => 'Fatou', 'sexe' => 'F', 'categorie' => 'sage_femme', 'poste' => 'Sage-femme principale', 'service' => 'Maternité', 'type_contrat' => 'CDI', 'salaire' => 320000],
            ['nom' => 'Bamba', 'prenom' => 'Youssouf', 'sexe' => 'M', 'categorie' => 'technicien_labo', 'poste' => 'Technicien principal', 'service' => 'Laboratoire', 'type_contrat' => 'CDI', 'salaire' => 300000],
            ['nom' => 'Cissé', 'prenom' => 'Aminata', 'sexe' => 'F', 'categorie' => 'technicien_radio', 'poste' => 'Technicienne imagerie', 'service' => 'Radiologie', 'type_contrat' => 'CDD', 'salaire' => 250000],
            ['nom' => 'Ouattara', 'prenom' => 'Drissa', 'sexe' => 'M', 'categorie' => 'aide_soignant', 'poste' => 'Aide-soignant', 'service' => 'Pédiatrie', 'type_contrat' => 'CDI', 'salaire' => 180000],
            ['nom' => 'Konaté', 'prenom' => 'Awa', 'sexe' => 'F', 'categorie' => 'agent_accueil', 'poste' => 'Hôtesse d\'accueil', 'service' => 'Réception', 'type_contrat' => 'CDD', 'salaire' => 150000],
            ['nom' => 'Sanogo', 'prenom' => 'Ibrahim', 'sexe' => 'M', 'categorie' => 'agent_entretien', 'poste' => 'Agent d\'entretien', 'service' => null, 'type_contrat' => 'CDI', 'salaire' => 120000],
            ['nom' => 'Diabaté', 'prenom' => 'Kouadio', 'sexe' => 'M', 'categorie' => 'securite', 'poste' => 'Agent de sécurité', 'service' => null, 'type_contrat' => 'Vacation', 'salaire' => 100000],
            ['nom' => 'Yao', 'prenom' => 'Marie-Claire', 'sexe' => 'F', 'categorie' => 'administratif', 'poste' => 'Secrétaire médicale', 'service' => 'Direction', 'type_contrat' => 'CDI', 'salaire' => 200000],
        ];

        foreach ($staff as $index => $data) {
            $data['matricule'] = 'EMP-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $data['date_naissance'] = Carbon::now()->subYears(rand(25, 55))->subDays(rand(0, 365))->format('Y-m-d');
            $data['date_embauche'] = Carbon::now()->subYears(rand(1, 10))->subDays(rand(0, 365))->format('Y-m-d');
            $data['telephone'] = '+225 0' . rand(1, 9) . ' ' . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT) . ' ' . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT) . ' ' . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            $data['statut'] = 'actif';

            Personnel::create($data);
        }
    }
}
