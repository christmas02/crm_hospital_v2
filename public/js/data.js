/**
 * Données mockées - Application Gestion Hospitalière
 */

const DATA = {
    // ==================== PATIENTS ====================
    patients: [
        // Patients adultes - Cas généraux
        { id: 1, nom: "Diallo", prenom: "Aminata", dateNaissance: "1990-05-15", sexe: "F", telephone: "+225 07 12 34 56", email: "aminata.diallo@email.com", adresse: "Cocody, Abidjan", groupeSanguin: "O+", allergies: ["Pénicilline"], dateInscription: "2024-01-10", statut: "actif" },
        { id: 2, nom: "Kouassi", prenom: "Jean-Marc", dateNaissance: "1978-11-22", sexe: "M", telephone: "+225 05 98 76 54", email: "jm.kouassi@email.com", adresse: "Plateau, Abidjan", groupeSanguin: "A+", allergies: [], dateInscription: "2024-01-15", statut: "actif" },
        { id: 3, nom: "Traoré", prenom: "Fatou", dateNaissance: "1995-03-08", sexe: "F", telephone: "+225 01 23 45 67", email: "fatou.traore@email.com", adresse: "Yopougon, Abidjan", groupeSanguin: "B+", allergies: ["Aspirine"], dateInscription: "2024-02-01", statut: "hospitalise" },
        { id: 4, nom: "Koné", prenom: "Moussa", dateNaissance: "1965-07-30", sexe: "M", telephone: "+225 07 65 43 21", email: "moussa.kone@email.com", adresse: "Marcory, Abidjan", groupeSanguin: "AB-", allergies: [], dateInscription: "2024-02-05", statut: "actif" },
        { id: 5, nom: "Bamba", prenom: "Mariam", dateNaissance: "2005-12-18", sexe: "F", telephone: "+225 05 11 22 33", email: "mariam.bamba@email.com", adresse: "Treichville, Abidjan", groupeSanguin: "O-", allergies: ["Latex"], dateInscription: "2024-02-10", statut: "actif" },
        { id: 6, nom: "Ouattara", prenom: "Ibrahim", dateNaissance: "1958-04-25", sexe: "M", telephone: "+225 01 99 88 77", email: "ibrahim.ouattara@email.com", adresse: "Adjamé, Abidjan", groupeSanguin: "A-", allergies: [], dateInscription: "2024-02-12", statut: "hospitalise" },
        { id: 7, nom: "Sanogo", prenom: "Aïcha", dateNaissance: "1982-09-14", sexe: "F", telephone: "+225 07 55 44 33", email: "aicha.sanogo@email.com", adresse: "Koumassi, Abidjan", groupeSanguin: "B-", allergies: [], dateInscription: "2024-02-15", statut: "actif" },
        { id: 8, nom: "Coulibaly", prenom: "Seydou", dateNaissance: "1988-01-05", sexe: "M", telephone: "+225 05 77 88 99", email: "seydou.coulibaly@email.com", adresse: "Abobo, Abidjan", groupeSanguin: "O+", allergies: ["Ibuprofène"], dateInscription: "2024-02-18", statut: "actif" },
        { id: 9, nom: "Cissé", prenom: "Kadiatou", dateNaissance: "1992-06-20", sexe: "F", telephone: "+225 07 33 22 11", email: "kadiatou.cisse@email.com", adresse: "Cocody, Abidjan", groupeSanguin: "A+", allergies: [], dateInscription: "2024-02-20", statut: "actif" },
        { id: 10, nom: "Touré", prenom: "Amadou", dateNaissance: "1975-10-12", sexe: "M", telephone: "+225 01 44 55 66", email: "amadou.toure@email.com", adresse: "Plateau, Abidjan", groupeSanguin: "AB+", allergies: [], dateInscription: "2024-02-22", statut: "hospitalise" },

        // Enfants et nourrissons - Pédiatrie
        { id: 11, nom: "Konaté", prenom: "Youssouf", dateNaissance: "2022-08-10", sexe: "M", telephone: "+225 07 11 11 11", email: "famille.konate@email.com", adresse: "Cocody, Abidjan", groupeSanguin: "O+", allergies: [], dateInscription: "2024-01-20", statut: "actif" },
        { id: 12, nom: "Diabaté", prenom: "Awa", dateNaissance: "2020-03-25", sexe: "F", telephone: "+225 05 22 22 22", email: "diabate.famille@email.com", adresse: "Yopougon, Abidjan", groupeSanguin: "A+", allergies: ["Arachides"], dateInscription: "2024-01-25", statut: "actif" },
        { id: 13, nom: "Soro", prenom: "Mamadou", dateNaissance: "2019-11-15", sexe: "M", telephone: "+225 01 33 33 33", email: "soro.parents@email.com", adresse: "Abobo, Abidjan", groupeSanguin: "B+", allergies: [], dateInscription: "2024-02-01", statut: "actif" },
        { id: 14, nom: "Bakayoko", prenom: "Salimata", dateNaissance: "2023-01-05", sexe: "F", telephone: "+225 07 44 44 44", email: "bakayoko.f@email.com", adresse: "Marcory, Abidjan", groupeSanguin: "O-", allergies: [], dateInscription: "2024-02-05", statut: "hospitalise" },

        // Femmes enceintes - Gynécologie/Obstétrique
        { id: 15, nom: "N'Guessan", prenom: "Marie-Claire", dateNaissance: "1993-07-20", sexe: "F", telephone: "+225 05 55 55 55", email: "marie.nguessan@email.com", adresse: "Cocody, Abidjan", groupeSanguin: "A+", allergies: [], dateInscription: "2024-01-08", statut: "actif" },
        { id: 16, nom: "Yapi", prenom: "Adjoua", dateNaissance: "1998-02-14", sexe: "F", telephone: "+225 01 66 66 66", email: "adjoua.yapi@email.com", adresse: "Plateau, Abidjan", groupeSanguin: "O+", allergies: ["Sulfamides"], dateInscription: "2024-01-12", statut: "actif" },
        { id: 17, nom: "Kouamé", prenom: "Akissi", dateNaissance: "1989-09-30", sexe: "F", telephone: "+225 07 77 77 77", email: "akissi.kouame@email.com", adresse: "Yopougon, Abidjan", groupeSanguin: "B-", allergies: [], dateInscription: "2024-01-18", statut: "hospitalise" },

        // Personnes âgées - Gériatrie
        { id: 18, nom: "Gbagbo", prenom: "Paul", dateNaissance: "1950-12-01", sexe: "M", telephone: "+225 05 88 88 88", email: "paul.gbagbo@email.com", adresse: "Adjamé, Abidjan", groupeSanguin: "A-", allergies: ["Codéine"], dateInscription: "2024-01-05", statut: "actif" },
        { id: 19, nom: "Bédié", prenom: "Henriette", dateNaissance: "1948-06-18", sexe: "F", telephone: "+225 01 99 99 99", email: "h.bedie@email.com", adresse: "Cocody, Abidjan", groupeSanguin: "AB+", allergies: [], dateInscription: "2024-01-08", statut: "hospitalise" },
        { id: 20, nom: "Gon", prenom: "Amadou", dateNaissance: "1955-03-22", sexe: "M", telephone: "+225 07 00 11 22", email: "amadou.gon@email.com", adresse: "Plateau, Abidjan", groupeSanguin: "O+", allergies: ["Morphine"], dateInscription: "2024-01-15", statut: "actif" },

        // Cas chroniques - Diabète, HTA, etc.
        { id: 21, nom: "Kourouma", prenom: "Fanta", dateNaissance: "1972-04-10", sexe: "F", telephone: "+225 05 11 22 33", email: "fanta.kourouma@email.com", adresse: "Koumassi, Abidjan", groupeSanguin: "A+", allergies: [], dateInscription: "2024-01-20", statut: "actif" },
        { id: 22, nom: "Ouédraogo", prenom: "Boubacar", dateNaissance: "1968-08-05", sexe: "M", telephone: "+225 01 22 33 44", email: "boubacar.o@email.com", adresse: "Abobo, Abidjan", groupeSanguin: "B+", allergies: ["Metformine"], dateInscription: "2024-01-22", statut: "actif" },
        { id: 23, nom: "Zadi", prenom: "Germaine", dateNaissance: "1960-11-28", sexe: "F", telephone: "+225 07 33 44 55", email: "germaine.zadi@email.com", adresse: "Treichville, Abidjan", groupeSanguin: "O-", allergies: [], dateInscription: "2024-01-25", statut: "actif" },

        // Cas d'urgence récents
        { id: 24, nom: "Fofana", prenom: "Ismaël", dateNaissance: "1985-01-17", sexe: "M", telephone: "+225 05 44 55 66", email: "ismael.fofana@email.com", adresse: "Marcory, Abidjan", groupeSanguin: "AB-", allergies: [], dateInscription: "2024-02-19", statut: "hospitalise" },
        { id: 25, nom: "Doumbia", prenom: "Rokia", dateNaissance: "1991-10-08", sexe: "F", telephone: "+225 01 55 66 77", email: "rokia.doumbia@email.com", adresse: "Cocody, Abidjan", groupeSanguin: "A+", allergies: ["Tramadol"], dateInscription: "2024-02-20", statut: "actif" },

        // Patients réguliers - Suivi
        { id: 26, nom: "Méité", prenom: "Lacina", dateNaissance: "1980-05-12", sexe: "M", telephone: "+225 07 66 77 88", email: "lacina.meite@email.com", adresse: "Yopougon, Abidjan", groupeSanguin: "O+", allergies: [], dateInscription: "2023-06-10", statut: "actif" },
        { id: 27, nom: "Brou", prenom: "Christelle", dateNaissance: "1994-12-03", sexe: "F", telephone: "+225 05 77 88 99", email: "christelle.brou@email.com", adresse: "Plateau, Abidjan", groupeSanguin: "B+", allergies: [], dateInscription: "2023-08-15", statut: "actif" },
        { id: 28, nom: "Aké", prenom: "Simplice", dateNaissance: "1970-02-28", sexe: "M", telephone: "+225 01 88 99 00", email: "simplice.ake@email.com", adresse: "Adjamé, Abidjan", groupeSanguin: "A-", allergies: ["Amoxicilline"], dateInscription: "2023-09-20", statut: "actif" },

        // Nouveaux patients du jour
        { id: 29, nom: "Koffi", prenom: "Eugène", dateNaissance: "1987-07-14", sexe: "M", telephone: "+225 07 99 00 11", email: "eugene.koffi@email.com", adresse: "Abobo, Abidjan", groupeSanguin: "O+", allergies: [], dateInscription: "2024-02-20", statut: "actif" },
        { id: 30, nom: "Aka", prenom: "Bintou", dateNaissance: "2001-04-22", sexe: "F", telephone: "+225 05 00 11 22", email: "bintou.aka@email.com", adresse: "Koumassi, Abidjan", groupeSanguin: "AB+", allergies: [], dateInscription: "2024-02-20", statut: "actif" },

        // Cas spéciaux
        { id: 31, nom: "Tanoh", prenom: "Vincent", dateNaissance: "1983-09-05", sexe: "M", telephone: "+225 01 11 22 33", email: "vincent.tanoh@email.com", adresse: "Cocody, Abidjan", groupeSanguin: "B-", allergies: ["Fruits de mer", "Iode"], dateInscription: "2024-02-18", statut: "actif" },
        { id: 32, nom: "Ehui", prenom: "Patricia", dateNaissance: "1976-06-30", sexe: "F", telephone: "+225 07 22 33 44", email: "patricia.ehui@email.com", adresse: "Marcory, Abidjan", groupeSanguin: "A+", allergies: [], dateInscription: "2024-02-15", statut: "actif" }
    ],

    // ==================== MÉDECINS ====================
    medecins: [
        { id: 1, nom: "Yao", prenom: "Kouadio", specialite: "Médecine générale", telephone: "+225 07 00 00 01", email: "dr.yao@hopital.ci", bureau: "A-101", statut: "disponible", tarifConsultation: 15000 },
        { id: 2, nom: "Touré", prenom: "Awa", specialite: "Gynécologie", telephone: "+225 07 00 00 02", email: "dr.toure@hopital.ci", bureau: "B-201", statut: "disponible", tarifConsultation: 25000 },
        { id: 3, nom: "Konaté", prenom: "Mamadou", specialite: "Cardiologie", telephone: "+225 07 00 00 03", email: "dr.konate@hopital.ci", bureau: "C-301", statut: "en_consultation", tarifConsultation: 30000 },
        { id: 4, nom: "Diabaté", prenom: "Fatoumata", specialite: "Pédiatrie", telephone: "+225 07 00 00 04", email: "dr.diabate@hopital.ci", bureau: "A-102", statut: "disponible", tarifConsultation: 20000 },
        { id: 5, nom: "Sylla", prenom: "Oumar", specialite: "Chirurgie", telephone: "+225 07 00 00 05", email: "dr.sylla@hopital.ci", bureau: "D-401", statut: "en_operation", tarifConsultation: 50000 },
        { id: 6, nom: "Camara", prenom: "Aminata", specialite: "Dermatologie", telephone: "+225 07 00 00 06", email: "dr.camara@hopital.ci", bureau: "B-202", statut: "absent", tarifConsultation: 20000 }
    ],

    // ==================== CONSULTATIONS ====================
    consultations: [
        // Consultations du jour - 20 février 2024
        { id: 1, patientId: 1, medecinId: 1, date: "2024-02-20", heure: "08:30", motif: "Fièvre et maux de tête", diagnostic: "Paludisme simple", statut: "termine", notes: "Traitement antipaludéen prescrit. TDR positif." },
        { id: 2, patientId: 2, medecinId: 3, date: "2024-02-20", heure: "09:00", motif: "Douleurs thoraciques", diagnostic: "Hypertension légère", statut: "termine", notes: "ECG normal, suivi recommandé. TA 150/95" },
        { id: 3, patientId: 3, medecinId: 2, date: "2024-02-20", heure: "09:30", motif: "Suivi grossesse", diagnostic: "Grossesse 28 semaines - RAS", statut: "termine", notes: "Écho normale, bébé en bonne santé" },
        { id: 4, patientId: 4, medecinId: 1, date: "2024-02-20", heure: "10:00", motif: "Contrôle diabète", diagnostic: "Diabète type 2 équilibré", statut: "termine", notes: "HbA1c 6.8%, continuer traitement" },
        { id: 5, patientId: 5, medecinId: 4, date: "2024-02-20", heure: "10:30", motif: "Vaccination rappel", diagnostic: "Vaccination effectuée", statut: "termine", notes: "DTP rappel administré" },
        { id: 6, patientId: 7, medecinId: 1, date: "2024-02-20", heure: "11:00", motif: "Douleurs articulaires", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 7, patientId: 8, medecinId: 3, date: "2024-02-20", heure: "11:30", motif: "Bilan cardiaque annuel", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 8, patientId: 9, medecinId: 2, date: "2024-02-20", heure: "14:00", motif: "Consultation prénatale", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 9, patientId: 11, medecinId: 4, date: "2024-02-20", heure: "14:30", motif: "Fièvre enfant 18 mois", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 10, patientId: 12, medecinId: 4, date: "2024-02-20", heure: "15:00", motif: "Toux persistante", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 11, patientId: 15, medecinId: 2, date: "2024-02-20", heure: "15:30", motif: "Échographie 2ème trimestre", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 12, patientId: 18, medecinId: 1, date: "2024-02-20", heure: "16:00", motif: "Douleurs lombaires", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 13, patientId: 21, medecinId: 3, date: "2024-02-20", heure: "16:30", motif: "Suivi hypertension", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 14, patientId: 29, medecinId: 1, date: "2024-02-20", heure: "17:00", motif: "Première consultation - fatigue", diagnostic: "", statut: "en_attente", notes: "" },
        { id: 15, patientId: 30, medecinId: 2, date: "2024-02-20", heure: "17:30", motif: "Règles irrégulières", diagnostic: "", statut: "en_attente", notes: "" },

        // Consultations passées - Historique varié
        { id: 16, patientId: 1, medecinId: 1, date: "2024-02-15", heure: "09:00", motif: "Grippe", diagnostic: "Syndrome grippal", statut: "termine", notes: "Repos et hydratation recommandés" },
        { id: 17, patientId: 2, medecinId: 3, date: "2024-02-10", heure: "10:00", motif: "Contrôle tension", diagnostic: "TA normalisée", statut: "termine", notes: "Continuer traitement Amlodipine" },
        { id: 18, patientId: 11, medecinId: 4, date: "2024-02-18", heure: "09:30", motif: "Vaccin ROR", diagnostic: "Vaccination effectuée", statut: "termine", notes: "ROR première dose" },
        { id: 19, patientId: 13, medecinId: 4, date: "2024-02-17", heure: "10:00", motif: "Otite", diagnostic: "Otite moyenne aiguë", statut: "termine", notes: "Antibiotiques prescrits 7 jours" },
        { id: 20, patientId: 15, medecinId: 2, date: "2024-02-12", heure: "11:00", motif: "Première consultation grossesse", diagnostic: "Grossesse confirmée 12 SA", statut: "termine", notes: "Bilan sanguin prescrit" },
        { id: 21, patientId: 16, medecinId: 2, date: "2024-02-08", heure: "14:00", motif: "Suivi grossesse 20 SA", diagnostic: "Grossesse normale", statut: "termine", notes: "Écho morpho prévue" },
        { id: 22, patientId: 18, medecinId: 1, date: "2024-02-05", heure: "09:00", motif: "Douleurs thoraciques", diagnostic: "Douleurs musculaires intercostales", statut: "termine", notes: "Anti-inflammatoires prescrits" },
        { id: 23, patientId: 19, medecinId: 3, date: "2024-02-14", heure: "10:30", motif: "Essoufflement", diagnostic: "Insuffisance cardiaque décompensée", statut: "termine", notes: "Hospitalisation recommandée" },
        { id: 24, patientId: 20, medecinId: 1, date: "2024-02-19", heure: "11:00", motif: "Contrôle prostate", diagnostic: "HBP stable", statut: "termine", notes: "PSA normal, continuer surveillance" },
        { id: 25, patientId: 21, medecinId: 3, date: "2024-02-06", heure: "09:00", motif: "Palpitations", diagnostic: "Extrasystoles bénignes", statut: "termine", notes: "Holter prescrit, éviter café" },
        { id: 26, patientId: 22, medecinId: 1, date: "2024-02-13", heure: "15:00", motif: "Contrôle glycémie", diagnostic: "Diabète déséquilibré", statut: "termine", notes: "Ajustement traitement insuline" },
        { id: 27, patientId: 23, medecinId: 3, date: "2024-02-11", heure: "16:00", motif: "Suivi post-infarctus", diagnostic: "Évolution favorable", statut: "termine", notes: "Rééducation cardiaque en cours" },
        { id: 28, patientId: 24, medecinId: 5, date: "2024-02-19", heure: "08:00", motif: "Accident de circulation", diagnostic: "Fracture tibia droit", statut: "termine", notes: "Chirurgie programmée" },
        { id: 29, patientId: 25, medecinId: 1, date: "2024-02-19", heure: "14:00", motif: "Migraine sévère", diagnostic: "Migraine avec aura", statut: "termine", notes: "Traitement de fond initié" },
        { id: 30, patientId: 26, medecinId: 1, date: "2024-02-18", heure: "10:00", motif: "Check-up annuel", diagnostic: "Bilan normal", statut: "termine", notes: "RAS, prochain contrôle dans 1 an" },
        { id: 31, patientId: 27, medecinId: 2, date: "2024-02-16", heure: "11:30", motif: "Douleurs pelviennes", diagnostic: "Kyste ovarien fonctionnel", statut: "termine", notes: "Échographie de contrôle dans 3 mois" },
        { id: 32, patientId: 28, medecinId: 1, date: "2024-02-15", heure: "15:30", motif: "Toux chronique", diagnostic: "Bronchite chronique", statut: "termine", notes: "Arrêt tabac conseillé, traitement prescrit" },
        { id: 33, patientId: 31, medecinId: 6, date: "2024-02-17", heure: "09:00", motif: "Éruption cutanée", diagnostic: "Eczéma atopique", statut: "termine", notes: "Dermocorticoïdes prescrits" },
        { id: 34, patientId: 32, medecinId: 2, date: "2024-02-14", heure: "14:30", motif: "Ménopause - bouffées de chaleur", diagnostic: "Syndrome climatérique", statut: "termine", notes: "THS discuté, phytothérapie proposée" },

        // Urgences récentes
        { id: 35, patientId: 14, medecinId: 4, date: "2024-02-20", heure: "07:30", motif: "Urgence - Convulsions fébriles", diagnostic: "Convulsions fébriles simples", statut: "termine", notes: "Hospitalisation pour surveillance 24h" },
        { id: 36, patientId: 17, medecinId: 2, date: "2024-02-19", heure: "23:00", motif: "Urgence - Contractions prématurées", diagnostic: "Menace accouchement prématuré", statut: "termine", notes: "Tocolyse, hospitalisation" },

        // Consultations en cours
        { id: 37, patientId: 6, medecinId: 3, date: "2024-02-20", heure: "08:00", motif: "Visite hospitalisation", diagnostic: "Insuffisance cardiaque - amélioration", statut: "en_cours", notes: "Diurétiques ajustés" }
    ],

    // ==================== DOSSIERS MÉDICAUX ====================
    dossiersMedicaux: [
        // Cas généraux
        { id: 1, patientId: 1, antecedents: ["Paludisme récurrent", "Anémie 2022"], maladiesChroniques: [], chirurgies: [], notes: "Patiente en bonne santé générale. Allergie à la pénicilline connue." },
        { id: 2, patientId: 2, antecedents: ["Hypertension découverte 2020"], maladiesChroniques: ["HTA stade 1"], chirurgies: [], notes: "Sous Amlodipine 5mg. TA bien contrôlée." },
        { id: 3, patientId: 3, antecedents: ["Fausse couche 2022"], maladiesChroniques: [], chirurgies: [], notes: "G2P0. Grossesse en cours 28 SA. Surveillance rapprochée." },
        { id: 4, patientId: 4, antecedents: ["Diabète familial père et mère"], maladiesChroniques: ["Diabète type 2"], chirurgies: ["Appendicectomie 2010"], notes: "Sous Metformine 1000mg x2/j. HbA1c trimestrielle." },
        { id: 5, patientId: 5, antecedents: [], maladiesChroniques: [], chirurgies: [], notes: "Adolescente. Carnet de vaccination à jour. Allergie latex." },
        { id: 6, patientId: 6, antecedents: ["AVC ischémique 2022", "Tabagisme sevré"], maladiesChroniques: ["HTA", "Diabète type 2", "Dyslipidémie"], chirurgies: [], notes: "Patient fragile, polymédicamenté. Surveillance neurologique." },
        { id: 7, patientId: 7, antecedents: ["Arthrose genou droit"], maladiesChroniques: [], chirurgies: ["Césarienne 2015"], notes: "Douleurs articulaires intermittentes." },
        { id: 8, patientId: 8, antecedents: [], maladiesChroniques: [], chirurgies: [], notes: "Patient sportif. Allergie Ibuprofène." },
        { id: 9, patientId: 9, antecedents: ["Anémie ferriprive"], maladiesChroniques: [], chirurgies: [], notes: "Supplémentation fer en cours." },
        { id: 10, patientId: 10, antecedents: ["Appendicectomie 2018"], maladiesChroniques: [], chirurgies: ["Appendicectomie 2018", "Réparation hernie 2024"], notes: "Post-op hernie inguinale. Bonne évolution." },

        // Dossiers pédiatriques
        { id: 11, patientId: 11, antecedents: ["Prématurité 35 SA"], maladiesChroniques: [], chirurgies: [], notes: "Nourrisson 18 mois. Développement normal. Vaccins à jour." },
        { id: 12, patientId: 12, antecedents: ["Bronchiolite 2022"], maladiesChroniques: ["Asthme du nourrisson"], chirurgies: [], notes: "Sous Ventoline si besoin. Allergie arachides." },
        { id: 13, patientId: 13, antecedents: ["Otites récurrentes"], maladiesChroniques: [], chirurgies: [], notes: "Enfant 4 ans. Adénoïdectomie envisagée." },
        { id: 14, patientId: 14, antecedents: ["Convulsions fébriles"], maladiesChroniques: [], chirurgies: [], notes: "Nourrisson 13 mois. Surveillance température stricte." },

        // Dossiers obstétrique
        { id: 15, patientId: 15, antecedents: [], maladiesChroniques: [], chirurgies: [], notes: "G1P0. Grossesse 16 SA. 1ère grossesse, RAS." },
        { id: 16, patientId: 16, antecedents: ["Grossesse extra-utérine 2021"], maladiesChroniques: [], chirurgies: ["Salpingectomie droite 2021"], notes: "G2P0. Grossesse 24 SA. Surveillance échographique rapprochée." },
        { id: 17, patientId: 17, antecedents: ["Césarienne 2018", "Diabète gestationnel 2018"], maladiesChroniques: [], chirurgies: ["Césarienne 2018"], notes: "G3P1. Grossesse 34 SA. Menace accouchement prématuré. Hospitalisée." },

        // Dossiers gériatrie
        { id: 18, patientId: 18, antecedents: ["Infarctus 2019", "Tabagisme sevré 2019"], maladiesChroniques: ["Cardiopathie ischémique", "HTA"], chirurgies: ["Stent coronaire 2019"], notes: "Patient 74 ans. Sous anticoagulants. Allergie codéine." },
        { id: 19, patientId: 19, antecedents: ["Fracture col fémur 2020"], maladiesChroniques: ["Insuffisance cardiaque", "Fibrillation auriculaire", "Ostéoporose"], chirurgies: ["Prothèse hanche 2020"], notes: "Patiente 76 ans. Grabataire. Soins palliatifs." },
        { id: 20, patientId: 20, antecedents: ["HBP"], maladiesChroniques: ["Hypertrophie bénigne prostate", "HTA"], chirurgies: [], notes: "Patient 69 ans. PSA surveillé. Allergie morphine." },

        // Dossiers maladies chroniques
        { id: 21, patientId: 21, antecedents: ["Diabète gestationnel 1998"], maladiesChroniques: ["Diabète type 2", "HTA", "Obésité"], chirurgies: ["Cholécystectomie 2015"], notes: "Patiente 52 ans. Syndrome métabolique. Suivi multidisciplinaire." },
        { id: 22, patientId: 22, antecedents: ["Rétinopathie diabétique"], maladiesChroniques: ["Diabète type 1", "Néphropathie diabétique"], chirurgies: [], notes: "Patient 56 ans. Insulino-dépendant. Allergie Metformine. Dialyse envisagée." },
        { id: 23, patientId: 23, antecedents: ["Infarctus 2021", "Angioplastie 2021"], maladiesChroniques: ["Cardiopathie ischémique", "Dyslipidémie"], chirurgies: ["Stent LAD 2021"], notes: "Patiente 64 ans. Rééducation cardiaque terminée. Bonne observance." },

        // Cas traumatologiques
        { id: 24, patientId: 24, antecedents: [], maladiesChroniques: [], chirurgies: [], notes: "Patient 39 ans. AVP. Fracture tibia droit. Chirurgie enclouage. Hospitalisé." },

        // Autres cas
        { id: 25, patientId: 25, antecedents: ["Migraines depuis adolescence"], maladiesChroniques: ["Migraine avec aura"], chirurgies: [], notes: "Patiente 33 ans. Traitement prophylactique initié. Allergie Tramadol." },
        { id: 26, patientId: 26, antecedents: [], maladiesChroniques: [], chirurgies: ["Hernie discale L4-L5 2022"], notes: "Patient 44 ans. Bonne récupération post-chirurgie." },
        { id: 27, patientId: 27, antecedents: ["Kyste ovarien 2023"], maladiesChroniques: [], chirurgies: [], notes: "Patiente 30 ans. Surveillance échographique." },
        { id: 28, patientId: 28, antecedents: ["Tuberculose pulmonaire 2010"], maladiesChroniques: ["BPCO stade 2"], chirurgies: [], notes: "Patient 54 ans. Ancien fumeur. EFR annuelle. Allergie Amoxicilline." },
        { id: 29, patientId: 31, antecedents: ["Eczéma enfance"], maladiesChroniques: ["Dermatite atopique"], chirurgies: [], notes: "Patient 41 ans. Allergies multiples (fruits de mer, iode)." },
        { id: 30, patientId: 32, antecedents: ["Hystérectomie 2018"], maladiesChroniques: ["Ménopause sous THS"], chirurgies: ["Hystérectomie totale 2018"], notes: "Patiente 48 ans. Suivi hormonal." }
    ],

    // ==================== PRESCRIPTIONS ====================
    prescriptions: [
        { id: 1, consultationId: 1, patientId: 1, medecinId: 1, date: "2024-02-20", medicaments: [
            { nom: "Arthémether-Luméfantrine", posologie: "2 cp 2x/jour", duree: "3 jours" },
            { nom: "Paracétamol 1g", posologie: "1 cp 3x/jour si fièvre", duree: "5 jours" }
        ]},
        { id: 2, consultationId: 2, patientId: 2, medecinId: 3, date: "2024-02-20", medicaments: [
            { nom: "Amlodipine 5mg", posologie: "1 cp le matin", duree: "30 jours" },
            { nom: "Aspirine 100mg", posologie: "1 cp le soir", duree: "30 jours" }
        ]},
        { id: 3, consultationId: 3, patientId: 3, medecinId: 2, date: "2024-02-20", medicaments: [
            { nom: "Fer + Acide folique", posologie: "1 cp/jour", duree: "30 jours" },
            { nom: "Calcium Vitamine D", posologie: "1 cp/jour", duree: "30 jours" }
        ]}
    ],

    // ==================== RENDEZ-VOUS ====================
    rendezvous: [
        { id: 1, patientId: 1, medecinId: 1, date: "2024-02-25", heure: "09:00", motif: "Contrôle post-traitement", statut: "confirme" },
        { id: 2, patientId: 3, medecinId: 2, date: "2024-03-05", heure: "10:00", motif: "Suivi grossesse", statut: "confirme" },
        { id: 3, patientId: 2, medecinId: 3, date: "2024-02-27", heure: "11:00", motif: "ECG de contrôle", statut: "confirme" },
        { id: 4, patientId: 5, medecinId: 4, date: "2024-02-22", heure: "14:00", motif: "Rappel vaccin", statut: "en_attente" },
        { id: 5, patientId: 7, medecinId: 6, date: "2024-02-23", heure: "09:30", motif: "Consultation dermatologique", statut: "confirme" },
        { id: 6, patientId: 9, medecinId: 2, date: "2024-02-28", heure: "10:30", motif: "Échographie", statut: "confirme" }
    ],

    // ==================== CHAMBRES ====================
    chambres: [
        { id: 1, numero: "101", etage: 1, type: "individuelle", capacite: 1, tarifJour: 50000, statut: "occupee", patientId: 3 },
        { id: 2, numero: "102", etage: 1, type: "individuelle", capacite: 1, tarifJour: 50000, statut: "libre", patientId: null },
        { id: 3, numero: "103", etage: 1, type: "double", capacite: 2, tarifJour: 35000, statut: "occupee", patientId: 6 },
        { id: 4, numero: "201", etage: 2, type: "individuelle", capacite: 1, tarifJour: 50000, statut: "occupee", patientId: 10 },
        { id: 5, numero: "202", etage: 2, type: "double", capacite: 2, tarifJour: 35000, statut: "libre", patientId: null },
        { id: 6, numero: "203", etage: 2, type: "double", capacite: 2, tarifJour: 35000, statut: "maintenance", patientId: null },
        { id: 7, numero: "301", etage: 3, type: "vip", capacite: 1, tarifJour: 100000, statut: "libre", patientId: null },
        { id: 8, numero: "302", etage: 3, type: "vip", capacite: 1, tarifJour: 100000, statut: "libre", patientId: null }
    ],

    // ==================== HOSPITALISATIONS ====================
    hospitalisations: [
        // Hospitalisations en cours
        { id: 1, patientId: 3, chambreId: 1, medecinId: 2, dateAdmission: "2024-02-18", dateSortie: null, motif: "Surveillance grossesse à risque - MAP", statut: "en_cours" },
        { id: 2, patientId: 6, chambreId: 3, medecinId: 3, dateAdmission: "2024-02-15", dateSortie: null, motif: "Insuffisance cardiaque décompensée", statut: "en_cours" },
        { id: 3, patientId: 10, chambreId: 4, medecinId: 5, dateAdmission: "2024-02-19", dateSortie: null, motif: "Post-opératoire hernie inguinale", statut: "en_cours" },
        { id: 4, patientId: 14, chambreId: 2, medecinId: 4, dateAdmission: "2024-02-20", dateSortie: null, motif: "Convulsions fébriles - Surveillance 24h", statut: "en_cours" },
        { id: 5, patientId: 17, chambreId: 7, medecinId: 2, dateAdmission: "2024-02-19", dateSortie: null, motif: "Menace accouchement prématuré 34 SA", statut: "en_cours" },
        { id: 6, patientId: 19, chambreId: 5, medecinId: 3, dateAdmission: "2024-02-14", dateSortie: null, motif: "Insuffisance cardiaque - Soins palliatifs", statut: "en_cours" },
        { id: 7, patientId: 24, chambreId: 8, medecinId: 5, dateAdmission: "2024-02-19", dateSortie: null, motif: "Fracture tibia - Attente chirurgie", statut: "en_cours" },

        // Hospitalisations terminées
        { id: 8, patientId: 1, chambreId: 2, medecinId: 1, dateAdmission: "2024-02-01", dateSortie: "2024-02-03", motif: "Paludisme sévère", statut: "termine" },
        { id: 9, patientId: 8, chambreId: 3, medecinId: 1, dateAdmission: "2024-01-25", dateSortie: "2024-01-28", motif: "Gastro-entérite aiguë - Déshydratation", statut: "termine" },
        { id: 10, patientId: 22, chambreId: 4, medecinId: 1, dateAdmission: "2024-02-05", dateSortie: "2024-02-12", motif: "Déséquilibre diabétique sévère", statut: "termine" }
    ],

    // ==================== MÉDICAMENTS (PHARMACIE) ====================
    medicaments: [
        { id: 1, nom: "Paracétamol 500mg", categorie: "Antalgique", forme: "Comprimé", stock: 500, stockMin: 100, prixUnitaire: 50, fournisseur: "Pharma CI" },
        { id: 2, nom: "Paracétamol 1g", categorie: "Antalgique", forme: "Comprimé", stock: 350, stockMin: 100, prixUnitaire: 75, fournisseur: "Pharma CI" },
        { id: 3, nom: "Amoxicilline 500mg", categorie: "Antibiotique", forme: "Gélule", stock: 200, stockMin: 50, prixUnitaire: 150, fournisseur: "MedAfrique" },
        { id: 4, nom: "Arthémether-Luméfantrine", categorie: "Antipaludéen", forme: "Comprimé", stock: 45, stockMin: 50, prixUnitaire: 2500, fournisseur: "Novartis" },
        { id: 5, nom: "Amlodipine 5mg", categorie: "Antihypertenseur", forme: "Comprimé", stock: 180, stockMin: 40, prixUnitaire: 200, fournisseur: "Sanofi" },
        { id: 6, nom: "Metformine 500mg", categorie: "Antidiabétique", forme: "Comprimé", stock: 300, stockMin: 60, prixUnitaire: 100, fournisseur: "MedAfrique" },
        { id: 7, nom: "Oméprazole 20mg", categorie: "Anti-ulcéreux", forme: "Gélule", stock: 150, stockMin: 30, prixUnitaire: 250, fournisseur: "Pharma CI" },
        { id: 8, nom: "Fer + Acide folique", categorie: "Supplément", forme: "Comprimé", stock: 400, stockMin: 100, prixUnitaire: 75, fournisseur: "Pharma CI" },
        { id: 9, nom: "Diclofénac 50mg", categorie: "Anti-inflammatoire", forme: "Comprimé", stock: 25, stockMin: 50, prixUnitaire: 100, fournisseur: "MedAfrique" },
        { id: 10, nom: "Sérum salé 0.9%", categorie: "Perfusion", forme: "Flacon 500ml", stock: 80, stockMin: 20, prixUnitaire: 1500, fournisseur: "B.Braun" },
        { id: 11, nom: "Insuline Rapide", categorie: "Antidiabétique", forme: "Flacon", stock: 15, stockMin: 10, prixUnitaire: 8000, fournisseur: "Novo Nordisk" },
        { id: 12, nom: "Aspirine 100mg", categorie: "Antiagrégant", forme: "Comprimé", stock: 250, stockMin: 50, prixUnitaire: 50, fournisseur: "Bayer" }
    ],

    // ==================== FICHES APPROVISIONNEMENT ====================
    fichesApprovisionnement: [
        {
            id: 1,
            numero: "APP-2024-001",
            date: "2024-02-15",
            fournisseur: "Pharma CI",
            lignes: [
                { medicamentId: 1, nom: "Paracétamol 500mg", quantite: 200, prixUnitaire: 50 },
                { medicamentId: 3, nom: "Amoxicilline 500mg", quantite: 100, prixUnitaire: 150 }
            ],
            totalArticles: 2,
            totalQuantite: 300,
            montantTotal: 25000,
            observations: "Commande mensuelle",
            creePar: "Pharmacien"
        },
        {
            id: 2,
            numero: "APP-2024-002",
            date: "2024-02-18",
            fournisseur: "MedAfrique",
            lignes: [
                { medicamentId: 4, nom: "Arthémether-Luméfantrine", quantite: 50, prixUnitaire: 2500 }
            ],
            totalArticles: 1,
            totalQuantite: 50,
            montantTotal: 125000,
            observations: "Réapprovisionnement urgent antipaludéens",
            creePar: "Pharmacien"
        }
    ],

    // ==================== MOUVEMENTS STOCK ====================
    mouvementsStock: [
        { id: 1, medicamentId: 1, type: "entree", quantite: 200, date: "2024-02-15", motif: "Réapprovisionnement" },
        { id: 2, medicamentId: 4, type: "sortie", quantite: 24, date: "2024-02-20", motif: "Prescription patient #1" },
        { id: 3, medicamentId: 5, type: "sortie", quantite: 30, date: "2024-02-20", motif: "Prescription patient #2" },
        { id: 4, medicamentId: 3, type: "entree", quantite: 100, date: "2024-02-18", motif: "Commande fournisseur" }
    ],

    // ==================== PAIEMENTS ====================
    paiements: [
        { id: 1, patientId: 1, date: "2024-02-20", montant: 15000, type: "consultation", description: "Consultation Dr. Yao", modePaiement: "especes", statut: "paye" },
        { id: 2, patientId: 1, date: "2024-02-20", montant: 7500, type: "medicaments", description: "Antipaludéen + Paracétamol", modePaiement: "mobile_money", statut: "paye" },
        { id: 3, patientId: 2, date: "2024-02-20", montant: 30000, type: "consultation", description: "Consultation cardiologie", modePaiement: "carte", statut: "paye" },
        { id: 4, patientId: 3, date: "2024-02-20", montant: 25000, type: "consultation", description: "Consultation prénatale", modePaiement: "especes", statut: "paye" },
        { id: 5, patientId: 3, date: "2024-02-18", montant: 50000, type: "hospitalisation", description: "Chambre 101 - 1 jour", modePaiement: "mobile_money", statut: "paye" },
        { id: 6, patientId: 6, date: "2024-02-15", montant: 175000, type: "hospitalisation", description: "Chambre 103 - 5 jours", modePaiement: "", statut: "en_attente" },
        { id: 7, patientId: 10, date: "2024-02-19", montant: 250000, type: "chirurgie", description: "Appendicectomie", modePaiement: "", statut: "en_attente" },
        { id: 8, patientId: 4, date: "2024-02-20", montant: 15000, type: "consultation", description: "Contrôle diabète", modePaiement: "especes", statut: "paye" }
    ],

    // ==================== TRANSACTIONS CAISSE ====================
    transactions: [
        { id: 1, date: "2024-02-20", type: "entree", montant: 15000, description: "Paiement consultation #1", categorie: "consultation" },
        { id: 2, date: "2024-02-20", type: "entree", montant: 7500, description: "Vente médicaments", categorie: "pharmacie" },
        { id: 3, date: "2024-02-20", type: "entree", montant: 30000, description: "Paiement consultation #3", categorie: "consultation" },
        { id: 4, date: "2024-02-20", type: "entree", montant: 25000, description: "Paiement consultation #4", categorie: "consultation" },
        { id: 5, date: "2024-02-20", type: "sortie", montant: 15000, description: "Achat fournitures", categorie: "depense" },
        { id: 6, date: "2024-02-20", type: "entree", montant: 50000, description: "Hospitalisation #5", categorie: "hospitalisation" },
        { id: 7, date: "2024-02-19", type: "entree", montant: 45000, description: "Consultations diverses", categorie: "consultation" },
        { id: 8, date: "2024-02-19", type: "sortie", montant: 80000, description: "Commande médicaments", categorie: "pharmacie" },
        { id: 9, date: "2024-02-18", type: "entree", montant: 120000, description: "Paiements divers", categorie: "autre" }
    ],

    // ==================== CATALOGUE ACTES MÉDICAUX ====================
    actesMedicaux: [
        // Consultations
        { id: 1, code: "CONS-GEN", nom: "Consultation générale", categorie: "consultation", prix: 15000, facturable: true },
        { id: 2, code: "CONS-SPE", nom: "Consultation spécialisée", categorie: "consultation", prix: 25000, facturable: true },
        { id: 3, code: "CONS-URG", nom: "Consultation urgence", categorie: "consultation", prix: 20000, facturable: true },
        // Examens
        { id: 4, code: "EXA-SANG", nom: "Bilan sanguin complet", categorie: "examen", prix: 25000, facturable: true },
        { id: 5, code: "EXA-URIN", nom: "Analyse urinaire", categorie: "examen", prix: 8000, facturable: true },
        { id: 6, code: "EXA-ECG", nom: "Électrocardiogramme (ECG)", categorie: "examen", prix: 15000, facturable: true },
        { id: 7, code: "EXA-ECHO", nom: "Échographie", categorie: "examen", prix: 35000, facturable: true },
        { id: 8, code: "EXA-RADIO", nom: "Radiographie", categorie: "examen", prix: 20000, facturable: true },
        { id: 9, code: "EXA-SCAN", nom: "Scanner", categorie: "examen", prix: 75000, facturable: true },
        { id: 10, code: "EXA-PALU", nom: "Test paludisme (TDR)", categorie: "examen", prix: 5000, facturable: true },
        { id: 11, code: "EXA-GLYC", nom: "Glycémie", categorie: "examen", prix: 3000, facturable: true },
        { id: 12, code: "EXA-TA", nom: "Prise de tension", categorie: "examen", prix: 0, facturable: false },
        // Soins
        { id: 13, code: "SOIN-INJ", nom: "Injection", categorie: "soin", prix: 2000, facturable: true },
        { id: 14, code: "SOIN-PERF", nom: "Perfusion", categorie: "soin", prix: 5000, facturable: true },
        { id: 15, code: "SOIN-PANS", nom: "Pansement simple", categorie: "soin", prix: 3000, facturable: true },
        { id: 16, code: "SOIN-PANC", nom: "Pansement complexe", categorie: "soin", prix: 8000, facturable: true },
        { id: 17, code: "SOIN-SUTU", nom: "Suture", categorie: "soin", prix: 15000, facturable: true },
        { id: 18, code: "SOIN-VACC", nom: "Vaccination", categorie: "soin", prix: 5000, facturable: true },
        { id: 19, code: "SOIN-NEB", nom: "Nébulisation", categorie: "soin", prix: 5000, facturable: true },
        // Actes spécialisés
        { id: 20, code: "ACT-PLATR", nom: "Pose de plâtre", categorie: "acte", prix: 25000, facturable: true },
        { id: 21, code: "ACT-SOND", nom: "Pose sonde urinaire", categorie: "acte", prix: 10000, facturable: true },
        { id: 22, code: "ACT-ACCOU", nom: "Accouchement normal", categorie: "acte", prix: 100000, facturable: true }
    ],

    // ==================== FICHES DE TRAITEMENT ====================
    fichesTraitement: [
        // Fiche 1 - Paludisme simple
        {
            id: 1,
            consultationId: 1,
            patientId: 1,
            medecinId: 1,
            date: "2024-02-20",
            actes: [
                { acteId: 1, nom: "Consultation générale", prix: 15000, quantite: 1, facturable: true },
                { acteId: 10, nom: "Test paludisme (TDR)", prix: 5000, quantite: 1, facturable: true },
                { acteId: 13, nom: "Injection", prix: 2000, quantite: 1, facturable: true }
            ],
            observations: "Patient présentant fièvre 39.2°C et céphalées depuis 3 jours. TDR positif Pf.",
            totalFacturable: 22000
        },
        // Fiche 2 - Bilan cardiaque
        {
            id: 2,
            consultationId: 2,
            patientId: 2,
            medecinId: 3,
            date: "2024-02-20",
            actes: [
                { acteId: 2, nom: "Consultation spécialisée", prix: 25000, quantite: 1, facturable: true },
                { acteId: 6, nom: "Électrocardiogramme (ECG)", prix: 15000, quantite: 1, facturable: true },
                { acteId: 12, nom: "Prise de tension", prix: 0, quantite: 1, facturable: false }
            ],
            observations: "Douleurs thoraciques atypiques. ECG normal. TA 150/95. Majoration traitement HTA.",
            totalFacturable: 40000
        },
        // Fiche 3 - Suivi grossesse
        {
            id: 3,
            consultationId: 3,
            patientId: 3,
            medecinId: 2,
            date: "2024-02-20",
            actes: [
                { acteId: 2, nom: "Consultation spécialisée", prix: 25000, quantite: 1, facturable: true },
                { acteId: 7, nom: "Échographie", prix: 35000, quantite: 1, facturable: true },
                { acteId: 12, nom: "Prise de tension", prix: 0, quantite: 1, facturable: false }
            ],
            observations: "G2P0, 28 SA. Écho: présentation céphalique, liquide normal, BIP conforme. Mouvements actifs.",
            totalFacturable: 60000
        },
        // Fiche 4 - Contrôle diabète
        {
            id: 4,
            consultationId: 4,
            patientId: 4,
            medecinId: 1,
            date: "2024-02-20",
            actes: [
                { acteId: 1, nom: "Consultation générale", prix: 15000, quantite: 1, facturable: true },
                { acteId: 11, nom: "Glycémie", prix: 3000, quantite: 1, facturable: true },
                { acteId: 12, nom: "Prise de tension", prix: 0, quantite: 1, facturable: false }
            ],
            observations: "Diabète type 2 équilibré. Glycémie à jeun 1.12g/L. HbA1c 6.8%. Continuer Metformine.",
            totalFacturable: 18000
        },
        // Fiche 5 - Vaccination pédiatrique
        {
            id: 5,
            consultationId: 5,
            patientId: 5,
            medecinId: 4,
            date: "2024-02-20",
            actes: [
                { acteId: 1, nom: "Consultation générale", prix: 15000, quantite: 1, facturable: true },
                { acteId: 18, nom: "Vaccination", prix: 5000, quantite: 1, facturable: true }
            ],
            observations: "Rappel DTP effectué. Pas de réaction allergique. Prochain rappel dans 1 an.",
            totalFacturable: 20000
        },
        // Fiche 6 - Otite pédiatrique
        {
            id: 6,
            consultationId: 19,
            patientId: 13,
            medecinId: 4,
            date: "2024-02-17",
            actes: [
                { acteId: 1, nom: "Consultation générale", prix: 15000, quantite: 1, facturable: true }
            ],
            observations: "Otite moyenne aiguë droite. Tympan bombé, hyperémique. Antibiotiques 7 jours.",
            totalFacturable: 15000
        },
        // Fiche 7 - Urgence cardiaque
        {
            id: 7,
            consultationId: 23,
            patientId: 19,
            medecinId: 3,
            date: "2024-02-14",
            actes: [
                { acteId: 3, nom: "Consultation urgence", prix: 20000, quantite: 1, facturable: true },
                { acteId: 6, nom: "Électrocardiogramme (ECG)", prix: 15000, quantite: 1, facturable: true },
                { acteId: 4, nom: "Bilan sanguin complet", prix: 25000, quantite: 1, facturable: true },
                { acteId: 14, nom: "Perfusion", prix: 5000, quantite: 2, facturable: true }
            ],
            observations: "Insuffisance cardiaque décompensée. OAP. Furosémide IV. Hospitalisation urgente.",
            totalFacturable: 70000
        },
        // Fiche 8 - Trauma fracture
        {
            id: 8,
            consultationId: 28,
            patientId: 24,
            medecinId: 5,
            date: "2024-02-19",
            actes: [
                { acteId: 3, nom: "Consultation urgence", prix: 20000, quantite: 1, facturable: true },
                { acteId: 8, nom: "Radiographie", prix: 20000, quantite: 2, facturable: true },
                { acteId: 13, nom: "Injection", prix: 2000, quantite: 2, facturable: true },
                { acteId: 20, nom: "Pose de plâtre", prix: 25000, quantite: 1, facturable: true }
            ],
            observations: "AVP moto. Fracture tibia droit 1/3 moyen. Plâtre cruro-pédieux. Chirurgie programmée.",
            totalFacturable: 89000
        },
        // Fiche 9 - Convulsions fébriles
        {
            id: 9,
            consultationId: 35,
            patientId: 14,
            medecinId: 4,
            date: "2024-02-20",
            actes: [
                { acteId: 3, nom: "Consultation urgence", prix: 20000, quantite: 1, facturable: true },
                { acteId: 10, nom: "Test paludisme (TDR)", prix: 5000, quantite: 1, facturable: true },
                { acteId: 4, nom: "Bilan sanguin complet", prix: 25000, quantite: 1, facturable: true },
                { acteId: 14, nom: "Perfusion", prix: 5000, quantite: 1, facturable: true }
            ],
            observations: "Convulsions fébriles simples. T°40.1°C. TDR négatif. NFS normale. Hospitalisation surveillance.",
            totalFacturable: 55000
        },
        // Fiche 10 - Check-up
        {
            id: 10,
            consultationId: 30,
            patientId: 26,
            medecinId: 1,
            date: "2024-02-18",
            actes: [
                { acteId: 1, nom: "Consultation générale", prix: 15000, quantite: 1, facturable: true },
                { acteId: 4, nom: "Bilan sanguin complet", prix: 25000, quantite: 1, facturable: true },
                { acteId: 5, nom: "Analyse urinaire", prix: 8000, quantite: 1, facturable: true },
                { acteId: 6, nom: "Électrocardiogramme (ECG)", prix: 15000, quantite: 1, facturable: true },
                { acteId: 12, nom: "Prise de tension", prix: 0, quantite: 1, facturable: false }
            ],
            observations: "Check-up annuel. Bilan complet normal. RAS. Prochain contrôle dans 1 an.",
            totalFacturable: 63000
        }
    ],

    // ==================== FACTURES (WORKFLOW) ====================
    factures: [
        // Factures payées
        {
            id: 1,
            numero: "FAC-2024-001",
            patientId: 1,
            consultationId: 1,
            ficheTraitementId: 1,
            date: "2024-02-20",
            lignes: [
                { description: "Consultation générale", quantite: 1, prixUnitaire: 15000, total: 15000 },
                { description: "Test paludisme (TDR)", quantite: 1, prixUnitaire: 5000, total: 5000 },
                { description: "Injection", quantite: 1, prixUnitaire: 2000, total: 2000 }
            ],
            montant: 22000,
            statut: "payee",
            envoyePar: "medecin",
            modePaiement: "especes",
            datePaiement: "2024-02-20"
        },
        {
            id: 2,
            numero: "FAC-2024-002",
            patientId: 2,
            consultationId: 2,
            ficheTraitementId: 2,
            date: "2024-02-20",
            lignes: [
                { description: "Consultation spécialisée", quantite: 1, prixUnitaire: 25000, total: 25000 },
                { description: "Électrocardiogramme (ECG)", quantite: 1, prixUnitaire: 15000, total: 15000 }
            ],
            montant: 40000,
            statut: "payee",
            envoyePar: "medecin",
            modePaiement: "carte",
            datePaiement: "2024-02-20"
        },
        {
            id: 3,
            numero: "FAC-2024-003",
            patientId: 13,
            consultationId: 19,
            ficheTraitementId: 6,
            date: "2024-02-17",
            lignes: [
                { description: "Consultation générale", quantite: 1, prixUnitaire: 15000, total: 15000 }
            ],
            montant: 15000,
            statut: "payee",
            envoyePar: "medecin",
            modePaiement: "mobile_money",
            datePaiement: "2024-02-17"
        },
        {
            id: 4,
            numero: "FAC-2024-004",
            patientId: 26,
            consultationId: 30,
            ficheTraitementId: 10,
            date: "2024-02-18",
            lignes: [
                { description: "Consultation générale", quantite: 1, prixUnitaire: 15000, total: 15000 },
                { description: "Bilan sanguin complet", quantite: 1, prixUnitaire: 25000, total: 25000 },
                { description: "Analyse urinaire", quantite: 1, prixUnitaire: 8000, total: 8000 },
                { description: "Électrocardiogramme (ECG)", quantite: 1, prixUnitaire: 15000, total: 15000 }
            ],
            montant: 63000,
            statut: "payee",
            envoyePar: "medecin",
            modePaiement: "carte",
            datePaiement: "2024-02-18"
        },
        // Factures en attente de paiement
        {
            id: 5,
            numero: "FAC-2024-005",
            patientId: 3,
            consultationId: 3,
            ficheTraitementId: 3,
            date: "2024-02-20",
            lignes: [
                { description: "Consultation spécialisée", quantite: 1, prixUnitaire: 25000, total: 25000 },
                { description: "Échographie", quantite: 1, prixUnitaire: 35000, total: 35000 }
            ],
            montant: 60000,
            statut: "en_attente",
            envoyePar: "medecin",
            modePaiement: "",
            datePaiement: null
        },
        {
            id: 6,
            numero: "FAC-2024-006",
            patientId: 4,
            consultationId: 4,
            ficheTraitementId: 4,
            date: "2024-02-20",
            lignes: [
                { description: "Consultation générale", quantite: 1, prixUnitaire: 15000, total: 15000 },
                { description: "Glycémie", quantite: 1, prixUnitaire: 3000, total: 3000 }
            ],
            montant: 18000,
            statut: "en_attente",
            envoyePar: "medecin",
            modePaiement: "",
            datePaiement: null
        },
        {
            id: 7,
            numero: "FAC-2024-007",
            patientId: 5,
            consultationId: 5,
            ficheTraitementId: 5,
            date: "2024-02-20",
            lignes: [
                { description: "Consultation générale", quantite: 1, prixUnitaire: 15000, total: 15000 },
                { description: "Vaccination", quantite: 1, prixUnitaire: 5000, total: 5000 }
            ],
            montant: 20000,
            statut: "en_attente",
            envoyePar: "medecin",
            modePaiement: "",
            datePaiement: null
        },
        {
            id: 8,
            numero: "FAC-2024-008",
            patientId: 19,
            consultationId: 23,
            ficheTraitementId: 7,
            date: "2024-02-14",
            lignes: [
                { description: "Consultation urgence", quantite: 1, prixUnitaire: 20000, total: 20000 },
                { description: "Électrocardiogramme (ECG)", quantite: 1, prixUnitaire: 15000, total: 15000 },
                { description: "Bilan sanguin complet", quantite: 1, prixUnitaire: 25000, total: 25000 },
                { description: "Perfusion", quantite: 2, prixUnitaire: 5000, total: 10000 }
            ],
            montant: 70000,
            statut: "en_attente",
            envoyePar: "medecin",
            modePaiement: "",
            datePaiement: null
        },
        {
            id: 9,
            numero: "FAC-2024-009",
            patientId: 24,
            consultationId: 28,
            ficheTraitementId: 8,
            date: "2024-02-19",
            lignes: [
                { description: "Consultation urgence", quantite: 1, prixUnitaire: 20000, total: 20000 },
                { description: "Radiographie", quantite: 2, prixUnitaire: 20000, total: 40000 },
                { description: "Injection", quantite: 2, prixUnitaire: 2000, total: 4000 },
                { description: "Pose de plâtre", quantite: 1, prixUnitaire: 25000, total: 25000 }
            ],
            montant: 89000,
            statut: "en_attente",
            envoyePar: "medecin",
            modePaiement: "",
            datePaiement: null
        },
        {
            id: 10,
            numero: "FAC-2024-010",
            patientId: 14,
            consultationId: 35,
            ficheTraitementId: 9,
            date: "2024-02-20",
            lignes: [
                { description: "Consultation urgence", quantite: 1, prixUnitaire: 20000, total: 20000 },
                { description: "Test paludisme (TDR)", quantite: 1, prixUnitaire: 5000, total: 5000 },
                { description: "Bilan sanguin complet", quantite: 1, prixUnitaire: 25000, total: 25000 },
                { description: "Perfusion", quantite: 1, prixUnitaire: 5000, total: 5000 }
            ],
            montant: 55000,
            statut: "en_attente",
            envoyePar: "medecin",
            modePaiement: "",
            datePaiement: null
        }
    ],

    // ==================== ORDONNANCES / DEMANDES DISPENSATION (PHARMACIE) ====================
    ordonnances: [
        // Ordonnances du jour
        {
            id: 1,
            consultationId: 1,
            patientId: 1,
            medecinId: 1,
            date: "2024-02-20",
            numeroRetrait: "RET-2024-001",
            medicaments: [
                { nom: "Arthémether-Luméfantrine", posologie: "2 cp 2x/jour", duree: "3 jours", quantite: 12 },
                { nom: "Paracétamol 1g", posologie: "1 cp 3x/jour si fièvre", duree: "5 jours", quantite: 15 }
            ],
            statutDispensation: "remis",
            datePreparation: "2024-02-20",
            dateRemise: "2024-02-20",
            remisA: "Infirmier Konan",
            recommandations: "Repos et bonne hydratation. Revenir si fièvre persiste."
        },
        {
            id: 2,
            consultationId: 2,
            patientId: 2,
            medecinId: 3,
            date: "2024-02-20",
            numeroRetrait: "RET-2024-002",
            medicaments: [
                { nom: "Amlodipine 5mg", posologie: "1 cp le matin", duree: "30 jours", quantite: 30 },
                { nom: "Aspirine 100mg", posologie: "1 cp le soir", duree: "30 jours", quantite: 30 }
            ],
            statutDispensation: "en_attente",
            datePreparation: null,
            dateRemise: null,
            remisA: null,
            recommandations: "Contrôle tension dans 1 mois. Régime hyposodé."
        },
        {
            id: 3,
            consultationId: 3,
            patientId: 3,
            medecinId: 2,
            date: "2024-02-20",
            numeroRetrait: "RET-2024-003",
            medicaments: [
                { nom: "Fer + Acide folique", posologie: "1 cp/jour", duree: "30 jours", quantite: 30 },
                { nom: "Calcium Vitamine D", posologie: "1 cp/jour", duree: "30 jours", quantite: 30 }
            ],
            statutDispensation: "prepare",
            datePreparation: "2024-02-20",
            dateRemise: null,
            remisA: null,
            recommandations: "Continuer suivi prénatal. Prochain RDV dans 2 semaines."
        },
        {
            id: 4,
            consultationId: 4,
            patientId: 4,
            medecinId: 1,
            date: "2024-02-20",
            numeroRetrait: "RET-2024-004",
            medicaments: [
                { nom: "Metformine 1000mg", posologie: "1 cp 2x/jour", duree: "30 jours", quantite: 60 },
                { nom: "Glibenclamide 5mg", posologie: "1 cp le matin", duree: "30 jours", quantite: 30 }
            ],
            statutDispensation: "en_attente",
            datePreparation: null,
            dateRemise: null,
            remisA: null,
            recommandations: "Régime diabétique strict. Contrôle glycémie à jeun."
        },
        {
            id: 5,
            consultationId: 5,
            patientId: 5,
            medecinId: 4,
            date: "2024-02-20",
            numeroRetrait: "RET-2024-005",
            medicaments: [
                { nom: "Paracétamol 500mg", posologie: "1 cp si fièvre/douleur", duree: "3 jours", quantite: 6 }
            ],
            statutDispensation: "remis",
            datePreparation: "2024-02-20",
            dateRemise: "2024-02-20",
            remisA: "Infirmier Diallo",
            recommandations: "Post-vaccination. Surveiller température."
        },

        // Ordonnances passées
        {
            id: 6,
            consultationId: 19,
            patientId: 13,
            medecinId: 4,
            date: "2024-02-17",
            numeroRetrait: "RET-2024-006",
            medicaments: [
                { nom: "Amoxicilline 250mg/5ml", posologie: "5ml 3x/jour", duree: "7 jours", quantite: 1 },
                { nom: "Paracétamol sirop", posologie: "5ml si fièvre", duree: "5 jours", quantite: 1 }
            ],
            statutDispensation: "remis",
            datePreparation: "2024-02-17",
            dateRemise: "2024-02-17",
            remisA: "Mère de l'enfant",
            recommandations: "Otite moyenne. Consulter si pas d'amélioration dans 48h."
        },
        {
            id: 7,
            consultationId: 22,
            patientId: 18,
            medecinId: 1,
            date: "2024-02-05",
            numeroRetrait: "RET-2024-007",
            medicaments: [
                { nom: "Diclofénac 50mg", posologie: "1 cp 2x/jour après repas", duree: "5 jours", quantite: 10 },
                { nom: "Oméprazole 20mg", posologie: "1 cp le matin à jeun", duree: "5 jours", quantite: 5 }
            ],
            statutDispensation: "remis",
            datePreparation: "2024-02-05",
            dateRemise: "2024-02-05",
            remisA: "Infirmier Konan",
            recommandations: "Douleurs intercostales. Éviter efforts physiques."
        },
        {
            id: 8,
            consultationId: 25,
            patientId: 21,
            medecinId: 3,
            date: "2024-02-06",
            numeroRetrait: "RET-2024-008",
            medicaments: [
                { nom: "Bisoprolol 5mg", posologie: "1 cp le matin", duree: "30 jours", quantite: 30 },
                { nom: "Amlodipine 10mg", posologie: "1 cp le soir", duree: "30 jours", quantite: 30 }
            ],
            statutDispensation: "remis",
            datePreparation: "2024-02-06",
            dateRemise: "2024-02-06",
            remisA: "Infirmier Bamba",
            recommandations: "Palpitations. Éviter café et alcool. Holter dans 15 jours."
        },
        {
            id: 9,
            consultationId: 29,
            patientId: 25,
            medecinId: 1,
            date: "2024-02-19",
            numeroRetrait: "RET-2024-009",
            medicaments: [
                { nom: "Sumatriptan 50mg", posologie: "1 cp au début de la crise", duree: "Selon besoin", quantite: 6 },
                { nom: "Topiramate 25mg", posologie: "1 cp le soir", duree: "30 jours", quantite: 30 }
            ],
            statutDispensation: "prepare",
            datePreparation: "2024-02-19",
            dateRemise: null,
            remisA: null,
            recommandations: "Migraine. Tenir journal des crises. Éviter facteurs déclenchants."
        },
        {
            id: 10,
            consultationId: 32,
            patientId: 28,
            medecinId: 1,
            date: "2024-02-15",
            numeroRetrait: "RET-2024-010",
            medicaments: [
                { nom: "Salbutamol spray", posologie: "2 bouffées si gêne", duree: "1 mois", quantite: 1 },
                { nom: "Fluticasone spray", posologie: "2 bouffées 2x/jour", duree: "1 mois", quantite: 1 },
                { nom: "Carbocistéine 750mg", posologie: "1 sachet 3x/jour", duree: "10 jours", quantite: 30 }
            ],
            statutDispensation: "remis",
            datePreparation: "2024-02-15",
            dateRemise: "2024-02-15",
            remisA: "Infirmier Konan",
            recommandations: "BPCO. Arrêt tabac impératif. Kinésithérapie respiratoire."
        },
        {
            id: 11,
            consultationId: 33,
            patientId: 31,
            medecinId: 6,
            date: "2024-02-17",
            numeroRetrait: "RET-2024-011",
            medicaments: [
                { nom: "Bétaméthasone crème", posologie: "Application 1x/jour", duree: "7 jours", quantite: 1 },
                { nom: "Cétirizine 10mg", posologie: "1 cp le soir", duree: "15 jours", quantite: 15 },
                { nom: "Crème émolliente", posologie: "Application 2x/jour", duree: "1 mois", quantite: 1 }
            ],
            statutDispensation: "remis",
            datePreparation: "2024-02-17",
            dateRemise: "2024-02-17",
            remisA: "Patient directement",
            recommandations: "Eczéma. Éviter savons agressifs. Hydrater régulièrement."
        },
        {
            id: 12,
            consultationId: 35,
            patientId: 14,
            medecinId: 4,
            date: "2024-02-20",
            numeroRetrait: "RET-2024-012",
            medicaments: [
                { nom: "Paracétamol suppositoire 150mg", posologie: "1 suppo si T>38.5°C", duree: "3 jours", quantite: 6 },
                { nom: "Diazépam rectal 5mg", posologie: "Si convulsion >3min", duree: "Urgence", quantite: 2 }
            ],
            statutDispensation: "en_attente",
            datePreparation: null,
            dateRemise: null,
            remisA: null,
            recommandations: "Convulsions fébriles. Surveillance température. Urgences si récidive."
        }
    ],

    // ==================== FILE ATTENTE MÉDECIN ====================
    fileAttente: [
        // File attente Dr. Yao (Médecine générale)
        { id: 1, consultationId: 6, patientId: 7, medecinId: 1, heureArrivee: "10:45", position: 1, statut: "en_attente" },
        { id: 2, consultationId: 12, patientId: 18, medecinId: 1, heureArrivee: "15:50", position: 2, statut: "en_attente" },
        { id: 3, consultationId: 14, patientId: 29, medecinId: 1, heureArrivee: "16:45", position: 3, statut: "en_attente" },

        // File attente Dr. Touré (Gynécologie)
        { id: 4, consultationId: 8, patientId: 9, medecinId: 2, heureArrivee: "13:50", position: 1, statut: "en_attente" },
        { id: 5, consultationId: 11, patientId: 15, medecinId: 2, heureArrivee: "15:20", position: 2, statut: "en_attente" },
        { id: 6, consultationId: 15, patientId: 30, medecinId: 2, heureArrivee: "17:15", position: 3, statut: "en_attente" },

        // File attente Dr. Konaté (Cardiologie)
        { id: 7, consultationId: 7, patientId: 8, medecinId: 3, heureArrivee: "11:20", position: 1, statut: "en_attente" },
        { id: 8, consultationId: 13, patientId: 21, medecinId: 3, heureArrivee: "16:20", position: 2, statut: "en_attente" },

        // File attente Dr. Diabaté (Pédiatrie)
        { id: 9, consultationId: 9, patientId: 11, medecinId: 4, heureArrivee: "14:15", position: 1, statut: "en_attente" },
        { id: 10, consultationId: 10, patientId: 12, medecinId: 4, heureArrivee: "14:50", position: 2, statut: "en_attente" }
    ],

    // ==================== PLANNING MÉDECINS ====================
    planning: [
        { medecinId: 1, jour: "lundi", debut: "08:00", fin: "16:00" },
        { medecinId: 1, jour: "mardi", debut: "08:00", fin: "16:00" },
        { medecinId: 1, jour: "mercredi", debut: "08:00", fin: "12:00" },
        { medecinId: 1, jour: "jeudi", debut: "08:00", fin: "16:00" },
        { medecinId: 1, jour: "vendredi", debut: "08:00", fin: "16:00" },
        { medecinId: 2, jour: "lundi", debut: "09:00", fin: "17:00" },
        { medecinId: 2, jour: "mardi", debut: "09:00", fin: "17:00" },
        { medecinId: 2, jour: "jeudi", debut: "09:00", fin: "17:00" },
        { medecinId: 2, jour: "vendredi", debut: "09:00", fin: "14:00" },
        { medecinId: 3, jour: "lundi", debut: "08:00", fin: "14:00" },
        { medecinId: 3, jour: "mercredi", debut: "08:00", fin: "14:00" },
        { medecinId: 3, jour: "vendredi", debut: "08:00", fin: "14:00" },
        { medecinId: 4, jour: "lundi", debut: "08:00", fin: "16:00" },
        { medecinId: 4, jour: "mardi", debut: "08:00", fin: "16:00" },
        { medecinId: 4, jour: "mercredi", debut: "08:00", fin: "16:00" },
        { medecinId: 4, jour: "jeudi", debut: "08:00", fin: "16:00" },
        { medecinId: 5, jour: "mardi", debut: "07:00", fin: "15:00" },
        { medecinId: 5, jour: "jeudi", debut: "07:00", fin: "15:00" },
        { medecinId: 6, jour: "lundi", debut: "10:00", fin: "16:00" },
        { medecinId: 6, jour: "mercredi", debut: "10:00", fin: "16:00" }
    ],

    // ==================== MÉTHODES UTILITAIRES ====================

    getPatientById(id) {
        return this.patients.find(p => p.id === id);
    },

    getMedecinById(id) {
        return this.medecins.find(m => m.id === id);
    },

    getChambreById(id) {
        return this.chambres.find(c => c.id === id);
    },

    getConsultationsByPatient(patientId) {
        return this.consultations.filter(c => c.patientId === patientId);
    },

    getConsultationsByMedecin(medecinId) {
        return this.consultations.filter(c => c.medecinId === medecinId);
    },

    getPrescriptionsByPatient(patientId) {
        return this.prescriptions.filter(p => p.patientId === patientId);
    },

    getRendezvousByMedecin(medecinId) {
        return this.rendezvous.filter(r => r.medecinId === medecinId);
    },

    getPlanningMedecin(medecinId) {
        return this.planning.filter(p => p.medecinId === medecinId);
    },

    getMedicamentsStockBas() {
        return this.medicaments.filter(m => m.stock <= m.stockMin);
    },

    getChambresLibres() {
        return this.chambres.filter(c => c.statut === "libre");
    },

    getHospitalisationsEnCours() {
        return this.hospitalisations.filter(h => h.statut === "en_cours");
    },

    getPaiementsEnAttente() {
        return this.paiements.filter(p => p.statut === "en_attente");
    },

    getFacturesEnAttente() {
        return this.factures.filter(f => f.statut === "en_attente");
    },

    getOrdonnancesATraiter() {
        return this.ordonnances.filter(o => o.statutDispensation === "en_attente" || o.statutDispensation === "prepare");
    },

    getOrdonnancesEnAttente() {
        return this.ordonnances.filter(o => o.statutDispensation === "en_attente");
    },

    getOrdonnancesPreparees() {
        return this.ordonnances.filter(o => o.statutDispensation === "prepare");
    },

    generateNumeroRetrait() {
        const count = this.ordonnances.length + 1;
        return `RET-2024-${String(count).padStart(3, '0')}`;
    },

    generateNumeroApprovisionnement() {
        const count = this.fichesApprovisionnement.length + 1;
        return `APP-2024-${String(count).padStart(3, '0')}`;
    },

    getFichesApprovisionnement() {
        return this.fichesApprovisionnement.slice().reverse();
    },

    getFileAttenteMedecin(medecinId) {
        return this.fileAttente.filter(f => f.medecinId === medecinId && f.statut === "en_attente");
    },

    getConsultationsEnAttenteMedecin(medecinId) {
        return this.consultations.filter(c => c.medecinId === medecinId && c.statut === "en_attente");
    },

    generateNumeroFacture() {
        const count = this.factures.length + 1;
        return `FAC-2024-${String(count).padStart(3, '0')}`;
    },

    getActesMedicauxByCategorie(categorie) {
        return this.actesMedicaux.filter(a => a.categorie === categorie);
    },

    getActeMedicalById(id) {
        return this.actesMedicaux.find(a => a.id === id);
    },

    getFicheTraitementByConsultation(consultationId) {
        return this.fichesTraitement.find(f => f.consultationId === consultationId);
    },

    getFactureByConsultation(consultationId) {
        return this.factures.find(f => f.consultationId === consultationId);
    },

    // Stats Dashboard
    getStats() {
        const today = "2024-02-20";
        const consultationsToday = this.consultations.filter(c => c.date === today);
        const paiementsToday = this.paiements.filter(p => p.date === today && p.statut === "paye");
        const recettesJour = paiementsToday.reduce((sum, p) => sum + p.montant, 0);
        const medecinsDisponibles = this.medecins.filter(m => m.statut === "disponible").length;
        const chambresOccupees = this.chambres.filter(c => c.statut === "occupee").length;
        const chambresTotal = this.chambres.length;

        return {
            totalPatients: this.patients.length,
            patientsHospitalises: this.patients.filter(p => p.statut === "hospitalise").length,
            consultationsJour: consultationsToday.length,
            consultationsEnAttente: consultationsToday.filter(c => c.statut === "en_attente").length,
            medecinsDisponibles,
            totalMedecins: this.medecins.length,
            recettesJour,
            chambresOccupees,
            chambresTotal,
            tauxOccupation: Math.round((chambresOccupees / chambresTotal) * 100),
            stockAlertes: this.getMedicamentsStockBas().length,
            paiementsEnAttente: this.getPaiementsEnAttente().reduce((sum, p) => sum + p.montant, 0)
        };
    },

    // Stats pour graphiques
    getStatsGraphiques() {
        return {
            consultationsParJour: [
                { jour: "Lun", count: 12 },
                { jour: "Mar", count: 15 },
                { jour: "Mer", count: 8 },
                { jour: "Jeu", count: 18 },
                { jour: "Ven", count: 14 },
                { jour: "Sam", count: 6 },
                { jour: "Dim", count: 2 }
            ],
            recettesParMois: [
                { mois: "Sep", montant: 2500000 },
                { mois: "Oct", montant: 3200000 },
                { mois: "Nov", montant: 2800000 },
                { mois: "Déc", montant: 3500000 },
                { mois: "Jan", montant: 2900000 },
                { mois: "Fév", montant: 3100000 }
            ],
            repartitionPaiements: [
                { type: "Consultations", montant: 1500000, pourcentage: 45 },
                { type: "Hospitalisation", montant: 1000000, pourcentage: 30 },
                { type: "Pharmacie", montant: 500000, pourcentage: 15 },
                { type: "Autres", montant: 333000, pourcentage: 10 }
            ]
        };
    }
};
