<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run()
    {
        $patients = [
            // Patients adultes - Cas généraux
            ['id' => 1, 'nom' => 'Diallo', 'prenom' => 'Aminata', 'date_naissance' => '1990-05-15', 'sexe' => 'F', 'telephone' => '+225 07 12 34 56', 'email' => 'aminata.diallo@email.com', 'adresse' => 'Cocody, Abidjan', 'groupe_sanguin' => 'O+', 'allergies' => ['Pénicilline'], 'date_inscription' => '2024-01-10', 'statut' => 'actif'],
            ['id' => 2, 'nom' => 'Kouassi', 'prenom' => 'Jean-Marc', 'date_naissance' => '1978-11-22', 'sexe' => 'M', 'telephone' => '+225 05 98 76 54', 'email' => 'jm.kouassi@email.com', 'adresse' => 'Plateau, Abidjan', 'groupe_sanguin' => 'A+', 'allergies' => [], 'date_inscription' => '2024-01-15', 'statut' => 'actif'],
            ['id' => 3, 'nom' => 'Traoré', 'prenom' => 'Fatou', 'date_naissance' => '1995-03-08', 'sexe' => 'F', 'telephone' => '+225 01 23 45 67', 'email' => 'fatou.traore@email.com', 'adresse' => 'Yopougon, Abidjan', 'groupe_sanguin' => 'B+', 'allergies' => ['Aspirine'], 'date_inscription' => '2024-02-01', 'statut' => 'hospitalise'],
            ['id' => 4, 'nom' => 'Koné', 'prenom' => 'Moussa', 'date_naissance' => '1965-07-30', 'sexe' => 'M', 'telephone' => '+225 07 65 43 21', 'email' => 'moussa.kone@email.com', 'adresse' => 'Marcory, Abidjan', 'groupe_sanguin' => 'AB-', 'allergies' => [], 'date_inscription' => '2024-02-05', 'statut' => 'actif'],
            ['id' => 5, 'nom' => 'Bamba', 'prenom' => 'Mariam', 'date_naissance' => '2005-12-18', 'sexe' => 'F', 'telephone' => '+225 05 11 22 33', 'email' => 'mariam.bamba@email.com', 'adresse' => 'Treichville, Abidjan', 'groupe_sanguin' => 'O-', 'allergies' => ['Latex'], 'date_inscription' => '2024-02-10', 'statut' => 'actif'],
            ['id' => 6, 'nom' => 'Ouattara', 'prenom' => 'Ibrahim', 'date_naissance' => '1958-04-25', 'sexe' => 'M', 'telephone' => '+225 01 99 88 77', 'email' => 'ibrahim.ouattara@email.com', 'adresse' => 'Adjamé, Abidjan', 'groupe_sanguin' => 'A-', 'allergies' => [], 'date_inscription' => '2024-02-12', 'statut' => 'hospitalise'],
            ['id' => 7, 'nom' => 'Sanogo', 'prenom' => 'Aïcha', 'date_naissance' => '1982-09-14', 'sexe' => 'F', 'telephone' => '+225 07 55 44 33', 'email' => 'aicha.sanogo@email.com', 'adresse' => 'Koumassi, Abidjan', 'groupe_sanguin' => 'B-', 'allergies' => [], 'date_inscription' => '2024-02-15', 'statut' => 'actif'],
            ['id' => 8, 'nom' => 'Coulibaly', 'prenom' => 'Seydou', 'date_naissance' => '1988-01-05', 'sexe' => 'M', 'telephone' => '+225 05 77 88 99', 'email' => 'seydou.coulibaly@email.com', 'adresse' => 'Abobo, Abidjan', 'groupe_sanguin' => 'O+', 'allergies' => ['Ibuprofène'], 'date_inscription' => '2024-02-18', 'statut' => 'actif'],
            ['id' => 9, 'nom' => 'Cissé', 'prenom' => 'Kadiatou', 'date_naissance' => '1992-06-20', 'sexe' => 'F', 'telephone' => '+225 07 33 22 11', 'email' => 'kadiatou.cisse@email.com', 'adresse' => 'Cocody, Abidjan', 'groupe_sanguin' => 'A+', 'allergies' => [], 'date_inscription' => '2024-02-20', 'statut' => 'actif'],
            ['id' => 10, 'nom' => 'Touré', 'prenom' => 'Amadou', 'date_naissance' => '1975-10-12', 'sexe' => 'M', 'telephone' => '+225 01 44 55 66', 'email' => 'amadou.toure@email.com', 'adresse' => 'Plateau, Abidjan', 'groupe_sanguin' => 'AB+', 'allergies' => [], 'date_inscription' => '2024-02-22', 'statut' => 'hospitalise'],
            // Enfants et nourrissons - Pédiatrie
            ['id' => 11, 'nom' => 'Konaté', 'prenom' => 'Youssouf', 'date_naissance' => '2022-08-10', 'sexe' => 'M', 'telephone' => '+225 07 11 11 11', 'email' => 'famille.konate@email.com', 'adresse' => 'Cocody, Abidjan', 'groupe_sanguin' => 'O+', 'allergies' => [], 'date_inscription' => '2024-01-20', 'statut' => 'actif'],
            ['id' => 12, 'nom' => 'Diabaté', 'prenom' => 'Awa', 'date_naissance' => '2020-03-25', 'sexe' => 'F', 'telephone' => '+225 05 22 22 22', 'email' => 'diabate.famille@email.com', 'adresse' => 'Yopougon, Abidjan', 'groupe_sanguin' => 'A+', 'allergies' => ['Arachides'], 'date_inscription' => '2024-01-25', 'statut' => 'actif'],
            ['id' => 13, 'nom' => 'Soro', 'prenom' => 'Mamadou', 'date_naissance' => '2019-11-15', 'sexe' => 'M', 'telephone' => '+225 01 33 33 33', 'email' => 'soro.parents@email.com', 'adresse' => 'Abobo, Abidjan', 'groupe_sanguin' => 'B+', 'allergies' => [], 'date_inscription' => '2024-02-01', 'statut' => 'actif'],
            ['id' => 14, 'nom' => 'Bakayoko', 'prenom' => 'Salimata', 'date_naissance' => '2023-01-05', 'sexe' => 'F', 'telephone' => '+225 07 44 44 44', 'email' => 'bakayoko.f@email.com', 'adresse' => 'Marcory, Abidjan', 'groupe_sanguin' => 'O-', 'allergies' => [], 'date_inscription' => '2024-02-05', 'statut' => 'hospitalise'],
            // Femmes enceintes - Gynécologie/Obstétrique
            ['id' => 15, 'nom' => "N'Guessan", 'prenom' => 'Marie-Claire', 'date_naissance' => '1993-07-20', 'sexe' => 'F', 'telephone' => '+225 05 55 55 55', 'email' => 'marie.nguessan@email.com', 'adresse' => 'Cocody, Abidjan', 'groupe_sanguin' => 'A+', 'allergies' => [], 'date_inscription' => '2024-01-08', 'statut' => 'actif'],
            ['id' => 16, 'nom' => 'Yapi', 'prenom' => 'Adjoua', 'date_naissance' => '1998-02-14', 'sexe' => 'F', 'telephone' => '+225 01 66 66 66', 'email' => 'adjoua.yapi@email.com', 'adresse' => 'Plateau, Abidjan', 'groupe_sanguin' => 'O+', 'allergies' => ['Sulfamides'], 'date_inscription' => '2024-01-12', 'statut' => 'actif'],
            ['id' => 17, 'nom' => 'Kouamé', 'prenom' => 'Akissi', 'date_naissance' => '1989-09-30', 'sexe' => 'F', 'telephone' => '+225 07 77 77 77', 'email' => 'akissi.kouame@email.com', 'adresse' => 'Yopougon, Abidjan', 'groupe_sanguin' => 'B-', 'allergies' => [], 'date_inscription' => '2024-01-18', 'statut' => 'hospitalise'],
            // Personnes âgées - Gériatrie
            ['id' => 18, 'nom' => 'Gbagbo', 'prenom' => 'Paul', 'date_naissance' => '1950-12-01', 'sexe' => 'M', 'telephone' => '+225 05 88 88 88', 'email' => 'paul.gbagbo@email.com', 'adresse' => 'Adjamé, Abidjan', 'groupe_sanguin' => 'A-', 'allergies' => ['Codéine'], 'date_inscription' => '2024-01-05', 'statut' => 'actif'],
            ['id' => 19, 'nom' => 'Bédié', 'prenom' => 'Henriette', 'date_naissance' => '1948-06-18', 'sexe' => 'F', 'telephone' => '+225 01 99 99 99', 'email' => 'h.bedie@email.com', 'adresse' => 'Cocody, Abidjan', 'groupe_sanguin' => 'AB+', 'allergies' => [], 'date_inscription' => '2024-01-08', 'statut' => 'hospitalise'],
            ['id' => 20, 'nom' => 'Gon', 'prenom' => 'Amadou', 'date_naissance' => '1955-03-22', 'sexe' => 'M', 'telephone' => '+225 07 00 11 22', 'email' => 'amadou.gon@email.com', 'adresse' => 'Plateau, Abidjan', 'groupe_sanguin' => 'O+', 'allergies' => ['Morphine'], 'date_inscription' => '2024-01-15', 'statut' => 'actif'],
            // Cas chroniques
            ['id' => 21, 'nom' => 'Kourouma', 'prenom' => 'Fanta', 'date_naissance' => '1972-04-10', 'sexe' => 'F', 'telephone' => '+225 05 11 22 33', 'email' => 'fanta.kourouma@email.com', 'adresse' => 'Koumassi, Abidjan', 'groupe_sanguin' => 'A+', 'allergies' => [], 'date_inscription' => '2024-01-20', 'statut' => 'actif'],
            ['id' => 22, 'nom' => 'Ouédraogo', 'prenom' => 'Boubacar', 'date_naissance' => '1968-08-05', 'sexe' => 'M', 'telephone' => '+225 01 22 33 44', 'email' => 'boubacar.o@email.com', 'adresse' => 'Abobo, Abidjan', 'groupe_sanguin' => 'B+', 'allergies' => ['Metformine'], 'date_inscription' => '2024-01-22', 'statut' => 'actif'],
            ['id' => 23, 'nom' => 'Zadi', 'prenom' => 'Germaine', 'date_naissance' => '1960-11-28', 'sexe' => 'F', 'telephone' => '+225 07 33 44 55', 'email' => 'germaine.zadi@email.com', 'adresse' => 'Treichville, Abidjan', 'groupe_sanguin' => 'O-', 'allergies' => [], 'date_inscription' => '2024-01-25', 'statut' => 'actif'],
            // Cas d'urgence récents
            ['id' => 24, 'nom' => 'Fofana', 'prenom' => 'Ismaël', 'date_naissance' => '1985-01-17', 'sexe' => 'M', 'telephone' => '+225 05 44 55 66', 'email' => 'ismael.fofana@email.com', 'adresse' => 'Marcory, Abidjan', 'groupe_sanguin' => 'AB-', 'allergies' => [], 'date_inscription' => '2024-02-19', 'statut' => 'hospitalise'],
            ['id' => 25, 'nom' => 'Doumbia', 'prenom' => 'Rokia', 'date_naissance' => '1991-10-08', 'sexe' => 'F', 'telephone' => '+225 01 55 66 77', 'email' => 'rokia.doumbia@email.com', 'adresse' => 'Cocody, Abidjan', 'groupe_sanguin' => 'A+', 'allergies' => ['Tramadol'], 'date_inscription' => '2024-02-20', 'statut' => 'actif'],
            // Patients réguliers
            ['id' => 26, 'nom' => 'Méité', 'prenom' => 'Lacina', 'date_naissance' => '1980-05-12', 'sexe' => 'M', 'telephone' => '+225 07 66 77 88', 'email' => 'lacina.meite@email.com', 'adresse' => 'Yopougon, Abidjan', 'groupe_sanguin' => 'O+', 'allergies' => [], 'date_inscription' => '2023-06-10', 'statut' => 'actif'],
            ['id' => 27, 'nom' => 'Brou', 'prenom' => 'Christelle', 'date_naissance' => '1994-12-03', 'sexe' => 'F', 'telephone' => '+225 05 77 88 99', 'email' => 'christelle.brou@email.com', 'adresse' => 'Plateau, Abidjan', 'groupe_sanguin' => 'B+', 'allergies' => [], 'date_inscription' => '2023-08-15', 'statut' => 'actif'],
            ['id' => 28, 'nom' => 'Aké', 'prenom' => 'Simplice', 'date_naissance' => '1970-02-28', 'sexe' => 'M', 'telephone' => '+225 01 88 99 00', 'email' => 'simplice.ake@email.com', 'adresse' => 'Adjamé, Abidjan', 'groupe_sanguin' => 'A-', 'allergies' => ['Amoxicilline'], 'date_inscription' => '2023-09-20', 'statut' => 'actif'],
            // Nouveaux patients du jour
            ['id' => 29, 'nom' => 'Koffi', 'prenom' => 'Eugène', 'date_naissance' => '1987-07-14', 'sexe' => 'M', 'telephone' => '+225 07 99 00 11', 'email' => 'eugene.koffi@email.com', 'adresse' => 'Abobo, Abidjan', 'groupe_sanguin' => 'O+', 'allergies' => [], 'date_inscription' => '2024-02-20', 'statut' => 'actif'],
            ['id' => 30, 'nom' => 'Aka', 'prenom' => 'Bintou', 'date_naissance' => '2001-04-22', 'sexe' => 'F', 'telephone' => '+225 05 00 11 22', 'email' => 'bintou.aka@email.com', 'adresse' => 'Koumassi, Abidjan', 'groupe_sanguin' => 'AB+', 'allergies' => [], 'date_inscription' => '2024-02-20', 'statut' => 'actif'],
            // Cas spéciaux
            ['id' => 31, 'nom' => 'Tanoh', 'prenom' => 'Vincent', 'date_naissance' => '1983-09-05', 'sexe' => 'M', 'telephone' => '+225 01 11 22 33', 'email' => 'vincent.tanoh@email.com', 'adresse' => 'Cocody, Abidjan', 'groupe_sanguin' => 'B-', 'allergies' => ['Fruits de mer', 'Iode'], 'date_inscription' => '2024-02-18', 'statut' => 'actif'],
            ['id' => 32, 'nom' => 'Ehui', 'prenom' => 'Patricia', 'date_naissance' => '1976-06-30', 'sexe' => 'F', 'telephone' => '+225 07 22 33 44', 'email' => 'patricia.ehui@email.com', 'adresse' => 'Marcory, Abidjan', 'groupe_sanguin' => 'A+', 'allergies' => [], 'date_inscription' => '2024-02-15', 'statut' => 'actif'],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }
    }
}
