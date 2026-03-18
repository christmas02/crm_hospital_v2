<?php

namespace Database\Seeders;

use App\Models\Medecin;
use Illuminate\Database\Seeder;

class MedecinSeeder extends Seeder
{
    public function run()
    {
        $medecins = [
            ['id' => 1, 'nom' => 'Yao', 'prenom' => 'Kouadio', 'specialite' => 'Médecine générale', 'telephone' => '+225 07 00 00 01', 'email' => 'dr.yao@hopital.ci', 'bureau' => 'A-101', 'statut' => 'disponible', 'tarif_consultation' => 15000],
            ['id' => 2, 'nom' => 'Touré', 'prenom' => 'Awa', 'specialite' => 'Gynécologie', 'telephone' => '+225 07 00 00 02', 'email' => 'dr.toure@hopital.ci', 'bureau' => 'B-201', 'statut' => 'disponible', 'tarif_consultation' => 25000],
            ['id' => 3, 'nom' => 'Konaté', 'prenom' => 'Mamadou', 'specialite' => 'Cardiologie', 'telephone' => '+225 07 00 00 03', 'email' => 'dr.konate@hopital.ci', 'bureau' => 'C-301', 'statut' => 'en_consultation', 'tarif_consultation' => 30000],
            ['id' => 4, 'nom' => 'Diabaté', 'prenom' => 'Fatoumata', 'specialite' => 'Pédiatrie', 'telephone' => '+225 07 00 00 04', 'email' => 'dr.diabate@hopital.ci', 'bureau' => 'A-102', 'statut' => 'disponible', 'tarif_consultation' => 20000],
            ['id' => 5, 'nom' => 'Sylla', 'prenom' => 'Oumar', 'specialite' => 'Chirurgie', 'telephone' => '+225 07 00 00 05', 'email' => 'dr.sylla@hopital.ci', 'bureau' => 'D-401', 'statut' => 'en_operation', 'tarif_consultation' => 50000],
            ['id' => 6, 'nom' => 'Camara', 'prenom' => 'Aminata', 'specialite' => 'Dermatologie', 'telephone' => '+225 07 00 00 06', 'email' => 'dr.camara@hopital.ci', 'bureau' => 'B-202', 'statut' => 'absent', 'tarif_consultation' => 20000],
        ];

        foreach ($medecins as $medecin) {
            Medecin::create($medecin);
        }

        // Lier le user médecin au premier médecin
        $medecinUser = \App\Models\User::where('email', 'medecin@medicare.ci')->first();
        $firstMedecin = Medecin::first();
        if ($medecinUser && $firstMedecin) {
            $firstMedecin->update(['user_id' => $medecinUser->id]);
        }
    }
}
