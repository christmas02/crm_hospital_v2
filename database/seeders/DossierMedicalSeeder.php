<?php

namespace Database\Seeders;

use App\Models\DossierMedical;
use Illuminate\Database\Seeder;

class DossierMedicalSeeder extends Seeder
{
    public function run()
    {
        $dossiers = [
            ['id' => 1, 'patient_id' => 1, 'antecedents' => ['Paludisme récurrent', 'Anémie 2022'], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Patiente en bonne santé générale. Allergie à la pénicilline connue.'],
            ['id' => 2, 'patient_id' => 2, 'antecedents' => ['Hypertension découverte 2020'], 'maladies_chroniques' => ['HTA stade 1'], 'chirurgies' => [], 'notes' => 'Sous Amlodipine 5mg. TA bien contrôlée.'],
            ['id' => 3, 'patient_id' => 3, 'antecedents' => ['Fausse couche 2022'], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'G2P0. Grossesse en cours 28 SA. Surveillance rapprochée.'],
            ['id' => 4, 'patient_id' => 4, 'antecedents' => ['Diabète familial père et mère'], 'maladies_chroniques' => ['Diabète type 2'], 'chirurgies' => ['Appendicectomie 2010'], 'notes' => 'Sous Metformine 1000mg x2/j. HbA1c trimestrielle.'],
            ['id' => 5, 'patient_id' => 5, 'antecedents' => [], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Adolescente. Carnet de vaccination à jour. Allergie latex.'],
            ['id' => 6, 'patient_id' => 6, 'antecedents' => ['AVC ischémique 2022', 'Tabagisme sevré'], 'maladies_chroniques' => ['HTA', 'Diabète type 2', 'Dyslipidémie'], 'chirurgies' => [], 'notes' => 'Patient fragile, polymédicamenté. Surveillance neurologique.'],
            ['id' => 7, 'patient_id' => 7, 'antecedents' => ['Arthrose genou droit'], 'maladies_chroniques' => [], 'chirurgies' => ['Césarienne 2015'], 'notes' => 'Douleurs articulaires intermittentes.'],
            ['id' => 8, 'patient_id' => 8, 'antecedents' => [], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Patient sportif. Allergie Ibuprofène.'],
            ['id' => 9, 'patient_id' => 9, 'antecedents' => ['Anémie ferriprive'], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Supplémentation fer en cours.'],
            ['id' => 10, 'patient_id' => 10, 'antecedents' => ['Appendicectomie 2018'], 'maladies_chroniques' => [], 'chirurgies' => ['Appendicectomie 2018', 'Réparation hernie 2024'], 'notes' => 'Post-op hernie inguinale. Bonne évolution.'],
            ['id' => 11, 'patient_id' => 11, 'antecedents' => ['Prématurité 35 SA'], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Nourrisson 18 mois. Développement normal. Vaccins à jour.'],
            ['id' => 12, 'patient_id' => 12, 'antecedents' => ['Bronchiolite 2022'], 'maladies_chroniques' => ['Asthme du nourrisson'], 'chirurgies' => [], 'notes' => 'Sous Ventoline si besoin. Allergie arachides.'],
            ['id' => 13, 'patient_id' => 13, 'antecedents' => ['Otites récurrentes'], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Enfant 4 ans. Adénoïdectomie envisagée.'],
            ['id' => 14, 'patient_id' => 14, 'antecedents' => ['Convulsions fébriles'], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Nourrisson 13 mois. Surveillance température stricte.'],
            ['id' => 15, 'patient_id' => 15, 'antecedents' => [], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'G1P0. Grossesse 16 SA. 1ère grossesse, RAS.'],
            ['id' => 16, 'patient_id' => 16, 'antecedents' => ['Grossesse extra-utérine 2021'], 'maladies_chroniques' => [], 'chirurgies' => ['Salpingectomie droite 2021'], 'notes' => 'G2P0. Grossesse 24 SA. Surveillance échographique rapprochée.'],
            ['id' => 17, 'patient_id' => 17, 'antecedents' => ['Césarienne 2018', 'Diabète gestationnel 2018'], 'maladies_chroniques' => [], 'chirurgies' => ['Césarienne 2018'], 'notes' => 'G3P1. Grossesse 34 SA. Menace accouchement prématuré. Hospitalisée.'],
            ['id' => 18, 'patient_id' => 18, 'antecedents' => ['Infarctus 2019', 'Tabagisme sevré 2019'], 'maladies_chroniques' => ['Cardiopathie ischémique', 'HTA'], 'chirurgies' => ['Stent coronaire 2019'], 'notes' => 'Patient 74 ans. Sous anticoagulants. Allergie codéine.'],
            ['id' => 19, 'patient_id' => 19, 'antecedents' => ['Fracture col fémur 2020'], 'maladies_chroniques' => ['Insuffisance cardiaque', 'Fibrillation auriculaire', 'Ostéoporose'], 'chirurgies' => ['Prothèse hanche 2020'], 'notes' => 'Patiente 76 ans. Grabataire. Soins palliatifs.'],
            ['id' => 20, 'patient_id' => 20, 'antecedents' => ['HBP'], 'maladies_chroniques' => ['Hypertrophie bénigne prostate', 'HTA'], 'chirurgies' => [], 'notes' => 'Patient 69 ans. PSA surveillé. Allergie morphine.'],
            ['id' => 21, 'patient_id' => 21, 'antecedents' => ['Diabète gestationnel 1998'], 'maladies_chroniques' => ['Diabète type 2', 'HTA', 'Obésité'], 'chirurgies' => ['Cholécystectomie 2015'], 'notes' => 'Patiente 52 ans. Syndrome métabolique. Suivi multidisciplinaire.'],
            ['id' => 22, 'patient_id' => 22, 'antecedents' => ['Rétinopathie diabétique'], 'maladies_chroniques' => ['Diabète type 1', 'Néphropathie diabétique'], 'chirurgies' => [], 'notes' => 'Patient 56 ans. Insulino-dépendant. Allergie Metformine. Dialyse envisagée.'],
            ['id' => 23, 'patient_id' => 23, 'antecedents' => ['Infarctus 2021', 'Angioplastie 2021'], 'maladies_chroniques' => ['Cardiopathie ischémique', 'Dyslipidémie'], 'chirurgies' => ['Stent LAD 2021'], 'notes' => 'Patiente 64 ans. Rééducation cardiaque terminée. Bonne observance.'],
            ['id' => 24, 'patient_id' => 24, 'antecedents' => [], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Patient 39 ans. AVP. Fracture tibia droit. Chirurgie enclouage. Hospitalisé.'],
            ['id' => 25, 'patient_id' => 25, 'antecedents' => ['Migraines depuis adolescence'], 'maladies_chroniques' => ['Migraine avec aura'], 'chirurgies' => [], 'notes' => 'Patiente 33 ans. Traitement prophylactique initié. Allergie Tramadol.'],
            ['id' => 26, 'patient_id' => 26, 'antecedents' => [], 'maladies_chroniques' => [], 'chirurgies' => ['Hernie discale L4-L5 2022'], 'notes' => 'Patient 44 ans. Bonne récupération post-chirurgie.'],
            ['id' => 27, 'patient_id' => 27, 'antecedents' => ['Kyste ovarien 2023'], 'maladies_chroniques' => [], 'chirurgies' => [], 'notes' => 'Patiente 30 ans. Surveillance échographique.'],
            ['id' => 28, 'patient_id' => 28, 'antecedents' => ['Tuberculose pulmonaire 2010'], 'maladies_chroniques' => ['BPCO stade 2'], 'chirurgies' => [], 'notes' => 'Patient 54 ans. Ancien fumeur. EFR annuelle. Allergie Amoxicilline.'],
            ['id' => 29, 'patient_id' => 31, 'antecedents' => ['Eczéma enfance'], 'maladies_chroniques' => ['Dermatite atopique'], 'chirurgies' => [], 'notes' => 'Patient 41 ans. Allergies multiples (fruits de mer, iode).'],
            ['id' => 30, 'patient_id' => 32, 'antecedents' => ['Hystérectomie 2018'], 'maladies_chroniques' => ['Ménopause sous THS'], 'chirurgies' => ['Hystérectomie totale 2018'], 'notes' => 'Patiente 48 ans. Suivi hormonal.'],
        ];

        foreach ($dossiers as $dossier) {
            DossierMedical::create($dossier);
        }
    }
}
