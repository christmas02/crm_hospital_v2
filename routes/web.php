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
use App\Http\Controllers\Admin\AdminController;
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
});

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/medecins', [AdminController::class, 'medecins'])->name('medecins');
    Route::post('/medecins', [AdminController::class, 'storeMedecin'])->name('medecins.store');
    Route::patch('/medecins/{medecin}/statut', [AdminController::class, 'updateMedecin'])->name('medecins.update');

    Route::get('/planning', [AdminController::class, 'planning'])->name('planning');
    Route::post('/rendezvous', [AdminController::class, 'storeRendezvous'])->name('rendezvous.store');

    Route::get('/hospitalisation', [AdminController::class, 'hospitalisation'])->name('hospitalisation');
    Route::post('/hospitalisation', [AdminController::class, 'storeAdmission'])->name('hospitalisation.store');
    Route::post('/hospitalisation/{hospitalisation}/sortie', [AdminController::class, 'sortieHospitalisation'])->name('hospitalisation.sortie');

    Route::get('/caisse', [AdminController::class, 'caisse'])->name('caisse');
    Route::post('/caisse/paiements', [AdminController::class, 'storePaiement'])->name('caisse.paiements.store');
    Route::post('/caisse/transactions', [AdminController::class, 'storeTransaction'])->name('caisse.transactions.store');
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

    // Consultations
    Route::get('/consultations', [ReceptionConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/create', [ReceptionConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/consultations', [ReceptionConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{consultation}', [ReceptionConsultationController::class, 'show'])->name('consultations.show');

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

    // Fiches de traitement
    Route::post('/consultations/{consultation}/fiche-traitement', [FicheTraitementController::class, 'store'])->name('fiches-traitement.store');

    // Ordonnances
    Route::post('/consultations/{consultation}/ordonnance', [OrdonnanceController::class, 'store'])->name('ordonnances.store');
});

// Routes Caisse
Route::middleware(['auth', 'role:caisse,admin'])->prefix('caisse')->name('caisse.')->group(function () {
    Route::get('/', [CaisseController::class, 'index'])->name('index');
    Route::get('/historique', [CaisseController::class, 'historique'])->name('historique');
    Route::get('/journal', [CaisseController::class, 'journal'])->name('journal');
    Route::post('/depenses', [CaisseController::class, 'storeDepense'])->name('depenses.store');

    // Factures
    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/{facture}', [FactureController::class, 'show'])->name('factures.show');
    Route::get('/factures/{facture}/details', [FactureController::class, 'details'])->name('factures.details');
    Route::post('/factures/{facture}/encaisser', [FactureController::class, 'encaisser'])->name('factures.encaisser');
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
    Route::post('/mouvements', [PharmacieController::class, 'storeMouvement'])->name('mouvements.store');

    // Stock
    Route::get('/stock/{medicament}', [StockController::class, 'show'])->name('stock.show');
    Route::post('/stock/{medicament}/ajuster', [StockController::class, 'ajuster'])->name('stock.ajuster');
});

require __DIR__.'/auth.php';
