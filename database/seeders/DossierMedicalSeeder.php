<?php

namespace Database\Seeders;

use App\Models\DossierMedical;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class DossierMedicalSeeder extends Seeder
{
    public function run()
    {
        $antecedents = [
            ['Paludisme récurrent', 'Anémie 2022'],
            ['Hypertension découverte 2020'],
            ['Fausse couche 2022'],
            ['Diabète familial père et mère'],
            [],
            ['AVC ischémique 2022', 'Tabagisme sevré'],
            ['Arthrose genou droit'],
            [],
            ['Anémie ferriprive'],
            ['Appendicectomie 2018'],
            ['Prématurité 35 SA'],
            ['Bronchiolite 2022'],
            ['Otites récurrentes'],
            ['Convulsions fébriles'],
            [],
            ['Grossesse extra-utérine 2021'],
            ['Césarienne 2018', 'Diabète gestationnel 2018'],
            ['Infarctus 2019', 'Tabagisme sevré 2019'],
            ['Fracture col fémur 2020'],
            ['HBP'],
        ];

        $chroniques = [
            [], ['HTA stade 1'], [], ['Diabète type 2'], [],
            ['HTA', 'Diabète type 2', 'Dyslipidémie'], [], [], [], [],
            [], ['Asthme du nourrisson'], [], [], [],
            [], [], ['Cardiopathie ischémique', 'HTA'],
            ['Insuffisance cardiaque', 'Fibrillation auriculaire'], ['HTA'],
        ];

        $chirurgies = [
            [], [], [], ['Appendicectomie 2010'], [],
            [], ['Césarienne 2015'], [], [], ['Appendicectomie 2018', 'Hernie 2024'],
            [], [], [], [], [],
            ['Salpingectomie droite 2021'], ['Césarienne 2018'], ['Stent coronaire 2019'],
            ['Prothèse hanche 2020'], [],
        ];

        $notes = [
            'Patiente en bonne santé générale. Allergie à la pénicilline connue.',
            'Sous Amlodipine 5mg. TA bien contrôlée.',
            'Surveillance rapprochée recommandée.',
            'Sous Metformine 1000mg x2/j. HbA1c trimestrielle.',
            'Carnet de vaccination à jour.',
            'Patient fragile, polymédicamenté. Surveillance neurologique.',
            'Douleurs articulaires intermittentes.',
            'Patient sportif. Bonne condition physique.',
            'Supplémentation fer en cours.',
            'Post-op hernie inguinale. Bonne évolution.',
            'Développement normal. Vaccins à jour.',
            'Sous Ventoline si besoin. Allergie arachides.',
            'Adénoïdectomie envisagée.',
            'Surveillance température stricte.',
            'Première grossesse, RAS.',
            'Surveillance échographique rapprochée.',
            'Menace accouchement prématuré.',
            'Sous anticoagulants. Allergie codéine.',
            'Soins palliatifs en cours.',
            'PSA surveillé. Allergie morphine.',
        ];

        // Créer un dossier médical pour CHAQUE patient
        $patients = Patient::all();

        foreach ($patients as $i => $patient) {
            DossierMedical::create([
                'patient_id' => $patient->id,
                'antecedents' => $antecedents[$i % count($antecedents)],
                'maladies_chroniques' => $chroniques[$i % count($chroniques)],
                'chirurgies' => $chirurgies[$i % count($chirurgies)],
                'notes' => $notes[$i % count($notes)],
            ]);
        }
    }
}
