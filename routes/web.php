<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Reception\ReceptionController;
use App\Http\Controllers\Reception\PatientController;
use App\Http\Controllers\Reception\ConsultationController as ReceptionConsultationController;
use App\Http\Controllers\Medecin\MedecinController;
use App\Http\Controllers\Medecin\ConsultationController as MedecinConsultationController;
use App\Http\Controllers\Medecin\FicheTraitementController;
use App\Http\Controllers\Medecin\OrdonnanceController;
use App\Http\Controllers\Caisse\CaisseController;
use App\Http\Controllers\Caisse\FactureController;
use App\Http\Controllers\Pharmacie\PharmacieController;
use App\Http\Controllers\Pharmacie\DispensationController;
use App\Http\Controllers\Pharmacie\StockController;
use App\Http\Controllers\Pharmacie\ApprovisionnementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Labo\LaboController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
});

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/medecins', [AdminController::class, 'medecins'])->name('medecins');
    Route::post('/medecins', [AdminController::class, 'storeMedecin'])->name('medecins.store');
    Route::patch('/medecins/{medecin}/statut', [AdminController::class, 'updateMedecin'])->name('medecins.update');
    Route::get('/medecins/{medecin}/json', [AdminController::class, 'showMedecinJson'])->name('medecins.json');
    Route::put('/medecins/{medecin}', [AdminController::class, 'updateMedecinFull'])->name('medecins.update-full');
    Route::post('/medecins/{medecin}/photo', [AdminController::class, 'updateMedecinPhoto'])->name('medecins.photo');
    Route::delete('/medecins/{medecin}', [AdminController::class, 'destroyMedecin'])->name('medecins.destroy');

    Route::get('/personnel', [PersonnelController::class, 'index'])->name('personnel.index');
    Route::post('/personnel', [PersonnelController::class, 'store'])->name('personnel.store');
    Route::get('/personnel/{personnel}/json', [PersonnelController::class, 'show'])->name('personnel.show');
    Route::put('/personnel/{personnel}', [PersonnelController::class, 'update'])->name('personnel.update');
    Route::delete('/personnel/{personnel}', [PersonnelController::class, 'destroy'])->name('personnel.destroy');

    Route::get('/planning', [AdminController::class, 'planning'])->name('planning');
    Route::post('/rendezvous', [AdminController::class, 'storeRendezvous'])->name('rendezvous.store');

    Route::get('/hospitalisation', [AdminController::class, 'hospitalisation'])->name('hospitalisation');
    Route::post('/hospitalisation', [AdminController::class, 'storeAdmission'])->name('hospitalisation.store');
    Route::post('/hospitalisation/{hospitalisation}/sortie', [AdminController::class, 'sortieHospitalisation'])->name('hospitalisation.sortie');

    Route::post('/chambres', [AdminController::class, 'storeChambre'])->name('chambres.store');
    Route::put('/chambres/{chambre}', [AdminController::class, 'updateChambre'])->name('chambres.update');
    Route::delete('/chambres/{chambre}', [AdminController::class, 'destroyChambre'])->name('chambres.destroy');
    Route::get('/chambres/{chambre}/json', [AdminController::class, 'chambreJson'])->name('chambres.json');

    Route::get('/caisse', [AdminController::class, 'caisse'])->name('caisse');
    Route::post('/caisse/paiements', [AdminController::class, 'storePaiement'])->name('caisse.paiements.store');
    Route::post('/caisse/transactions', [AdminController::class, 'storeTransaction'])->name('caisse.transactions.store');

    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/audit-log', [AdminController::class, 'auditLog'])->name('audit-log');

    Route::get('/rapports', function() { return view('admin.rapports'); })->name('rapports');
    Route::get('/rapport-mensuel', [AdminController::class, 'monthlyReport'])->name('rapport-mensuel');

    Route::get('/rappels', [AdminController::class, 'rappels'])->name('rappels-rdv');
    Route::post('/rappels/envoyer', [AdminController::class, 'envoyerTousRappels'])->name('rappels-rdv.envoyer');
});

// Routes Réception
Route::middleware(['auth', 'role:reception,admin'])->prefix('reception')->name('reception.')->group(function () {
    Route::get('/', [ReceptionController::class, 'index'])->name('index');

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
    Route::get('/patients/{patient}/json', [PatientController::class, 'showJson'])->name('patients.json');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::post('/patients/{patient}/documents', [PatientController::class, 'storeDocument'])->name('patients.documents.store');
    Route::delete('/patients/{patient}/documents/{document}', [PatientController::class, 'destroyDocument'])->name('patients.documents.destroy');
    Route::post('/patients/{patient}/vaccinations', [PatientController::class, 'storeVaccination'])->name('patients.vaccinations.store');
    Route::get('/patients/{patient}/carnet', [PatientController::class, 'carnetSante'])->name('patients.carnet');
    Route::get('/patients/{patient}/carnet/pdf', [PatientController::class, 'carnetSantePdf'])->name('patients.carnet.pdf');

    // Consultations
    Route::get('/consultations', [ReceptionConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/create', [ReceptionConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/consultations', [ReceptionConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{consultation}/edit', [ReceptionConsultationController::class, 'edit'])->name('consultations.edit');
    Route::put('/consultations/{consultation}', [ReceptionConsultationController::class, 'update'])->name('consultations.update');
    Route::get('/consultations/{consultation}/json', [ReceptionConsultationController::class, 'showJson'])->name('consultations.json');
    Route::get('/consultations/{consultation}', [ReceptionConsultationController::class, 'show'])->name('consultations.show');
    Route::delete('/consultations/{consultation}', [ReceptionConsultationController::class, 'destroy'])->name('consultations.destroy');
    Route::post('/consultations/{consultation}/rappel', [ReceptionConsultationController::class, 'envoyerRappel'])->name('consultations.rappel');

    // Facturation
    Route::get('/factures', [ReceptionController::class, 'factures'])->name('factures.index');
});

// Routes Médecin
Route::middleware(['auth', 'role:medecin,admin'])->prefix('medecin')->name('medecin.')->group(function () {
    Route::get('/', [MedecinController::class, 'index'])->name('index');
    Route::get('/file-attente', [MedecinController::class, 'fileAttente'])->name('file-attente');
    Route::get('/dossiers', [MedecinController::class, 'dossiers'])->name('dossiers');
    Route::get('/fiches', [MedecinController::class, 'fichesTraitement'])->name('fiches');
    Route::get('/ordonnances', [MedecinController::class, 'ordonnances'])->name('ordonnances');

    // Consultations
    Route::get('/consultations/{consultation}', [MedecinConsultationController::class, 'show'])->name('consultations.show');
    Route::post('/consultations/{consultation}/start', [MedecinConsultationController::class, 'start'])->name('consultations.start');
    Route::put('/consultations/{consultation}', [MedecinConsultationController::class, 'update'])->name('consultations.update');
    Route::post('/consultations/{consultation}/terminer', [MedecinConsultationController::class, 'terminer'])->name('consultations.terminer');
    Route::post('/consultations/{consultation}/notes', [MedecinConsultationController::class, 'storeNote'])->name('consultations.notes.store');
    Route::post('/consultations/{consultation}/signes-vitaux', [MedecinConsultationController::class, 'storeSignesVitaux'])->name('consultations.signes-vitaux.store');
    Route::post('/consultations/{consultation}/certificat', [MedecinConsultationController::class, 'storeCertificat'])->name('consultations.certificat.store');
    Route::post('/consultations/{consultation}/reference', [MedecinConsultationController::class, 'storeReference'])->name('consultations.reference.store');
    Route::get('/certificats/{certificat}/pdf', [MedecinConsultationController::class, 'certificatPdf'])->name('certificats.pdf');

    // Fiches de traitement
    Route::post('/consultations/{consultation}/fiche-traitement', [FicheTraitementController::class, 'store'])->name('fiches-traitement.store');

    // Ordonnances
    Route::post('/consultations/{consultation}/ordonnance', [OrdonnanceController::class, 'store'])->name('ordonnances.store');
    Route::get('/ordonnances/{ordonnance}/json', [OrdonnanceController::class, 'showJson'])->name('ordonnances.json');
    Route::put('/ordonnances/{ordonnance}', [OrdonnanceController::class, 'update'])->name('ordonnances.update');
    Route::get('/ordonnances/{ordonnance}/pdf', [OrdonnanceController::class, 'pdf'])->name('ordonnances.pdf');
    Route::delete('/ordonnances/{ordonnance}', [OrdonnanceController::class, 'destroy'])->name('ordonnances.destroy');
});

// Routes Caisse
Route::middleware(['auth', 'role:caisse,admin'])->prefix('caisse')->name('caisse.')->group(function () {
    Route::get('/', [CaisseController::class, 'index'])->name('index');
    Route::get('/historique', [CaisseController::class, 'historique'])->name('historique');
    Route::get('/journal', [CaisseController::class, 'journal'])->name('journal');
    Route::get('/journal/pdf', [CaisseController::class, 'journalPdf'])->name('journal.pdf');
    Route::get('/rapport-journalier', [CaisseController::class, 'rapportJournalier'])->name('rapport-journalier');
    Route::post('/depenses', [CaisseController::class, 'storeDepense'])->name('depenses.store');
    Route::get('/creances', [CaisseController::class, 'creances'])->name('creances');
    Route::get('/prise-en-charge', [CaisseController::class, 'priseEnCharge'])->name('prise-en-charge');

    // Sessions de caisse
    Route::post('/session/ouvrir', [CaisseController::class, 'ouvrirSession'])->name('session.ouvrir');
    Route::post('/session/fermer', [CaisseController::class, 'fermerSession'])->name('session.fermer');
    Route::get('/sessions', [CaisseController::class, 'sessionsHistorique'])->name('sessions');

    // Factures
    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/{facture}', [FactureController::class, 'show'])->name('factures.show');
    Route::get('/factures/{facture}/details', [FactureController::class, 'details'])->name('factures.details');
    Route::post('/factures/{facture}/encaisser', [FactureController::class, 'encaisser'])->name('factures.encaisser');
    Route::get('/factures/{facture}/pdf', [FactureController::class, 'pdf'])->name('factures.pdf');
    Route::get('/factures/{facture}/recu/{paiement}', [FactureController::class, 'recu'])->name('factures.recu');
    Route::post('/factures/{facture}/avoir', [FactureController::class, 'storeAvoir'])->name('factures.avoir');
    Route::post('/factures/{facture}/annuler', [FactureController::class, 'annuler'])->name('factures.annuler');
    Route::post('/factures/{facture}/remboursement/{paiement}', [FactureController::class, 'storeRemboursement'])->name('factures.remboursement');
    Route::post('/factures/{facture}/prise-en-charge', [FactureController::class, 'appliquerPriseEnCharge'])->name('factures.prise-en-charge');
    Route::delete('/factures/{facture}', [FactureController::class, 'destroy'])->name('factures.destroy');

    // Relevé de compte patient
    Route::get('/releve/{patient}', [FactureController::class, 'releve'])->name('releve');

    // Solde patient
    Route::get('/patients/{patient}/solde', [FactureController::class, 'soldePatient'])->name('patients.solde');
});

// Routes Pharmacie
Route::middleware(['auth', 'role:pharmacie,admin'])->prefix('pharmacie')->name('pharmacie.')->group(function () {
    Route::get('/', [PharmacieController::class, 'index'])->name('index');
    Route::get('/stock', [PharmacieController::class, 'stock'])->name('stock');
    Route::get('/demandes', [PharmacieController::class, 'demandes'])->name('demandes');
    Route::get('/alertes', [PharmacieController::class, 'alertes'])->name('alertes');
    Route::get('/mouvements', [PharmacieController::class, 'mouvements'])->name('mouvements');
    Route::get('/approvisionnements', [PharmacieController::class, 'approvisionnements'])->name('approvisionnements');

    // Dispensation
    Route::post('/ordonnances/{ordonnance}/preparer', [DispensationController::class, 'preparer'])->name('ordonnances.preparer');
    Route::post('/ordonnances/{ordonnance}/remettre', [DispensationController::class, 'remettre'])->name('ordonnances.remettre');

    // Médicaments
    Route::post('/medicaments', [PharmacieController::class, 'storeMedicament'])->name('medicaments.store');
    Route::put('/medicaments/{medicament}', [PharmacieController::class, 'updateMedicament'])->name('medicaments.update');
    Route::delete('/medicaments/{medicament}', [PharmacieController::class, 'destroyMedicament'])->name('medicaments.destroy');
    Route::post('/mouvements', [PharmacieController::class, 'storeMouvement'])->name('mouvements.store');

    // Stock
    Route::get('/stock/{medicament}', [StockController::class, 'show'])->name('stock.show');
    Route::post('/stock/{medicament}/ajuster', [StockController::class, 'ajuster'])->name('stock.ajuster');

    // Approvisionnements CRUD
    Route::post('/approvisionnements', [ApprovisionnementController::class, 'store'])->name('approvisionnements.store');
    Route::post('/approvisionnements/{approvisionnement}/valider', [ApprovisionnementController::class, 'valider'])->name('approvisionnements.valider');
    Route::delete('/approvisionnements/{approvisionnement}', [ApprovisionnementController::class, 'destroy'])->name('approvisionnements.destroy');
    Route::get('/approvisionnements/{approvisionnement}/json', [ApprovisionnementController::class, 'show'])->name('approvisionnements.show');
});

// Routes Laboratoire
Route::middleware(['auth', 'role:medecin,admin,reception'])->prefix('labo')->name('labo.')->group(function () {
    Route::get('/', [LaboController::class, 'index'])->name('index');
    Route::post('/demandes', [LaboController::class, 'store'])->name('demandes.store');
    Route::patch('/demandes/{demande}/statut', [LaboController::class, 'updateStatut'])->name('demandes.statut');
    Route::post('/demandes/{demande}/resultats', [LaboController::class, 'saisirResultats'])->name('demandes.resultats');
    Route::get('/demandes/{demande}/json', [LaboController::class, 'show'])->name('demandes.show');
    Route::get('/demandes/{demande}/pdf', [LaboController::class, 'resultsPdf'])->name('demandes.pdf');
    Route::get('/examens', [LaboController::class, 'examens'])->name('examens');
    Route::post('/examens', [LaboController::class, 'storeExamen'])->name('examens.store');
});

// Routes Export CSV
Route::middleware('auth')->prefix('export')->name('export.')->group(function () {
    Route::get('/patients', [ExportController::class, 'patients'])->name('patients');
    Route::get('/consultations', [ExportController::class, 'consultations'])->name('consultations');
    Route::get('/medecins', [ExportController::class, 'medecins'])->name('medecins');
    Route::get('/medicaments', [ExportController::class, 'medicaments'])->name('medicaments');
    Route::get('/factures', [ExportController::class, 'factures'])->name('factures');
    Route::get('/transactions', [ExportController::class, 'transactions'])->name('transactions');
});

require __DIR__.'/auth.php';
