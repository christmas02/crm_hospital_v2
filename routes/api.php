<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Stats
    Route::get('/stats', [ApiController::class, 'stats']);

    // Patients
    Route::get('/patients', [ApiController::class, 'patients']);
    Route::post('/patients', [ApiController::class, 'patientStore']);
    Route::get('/patients/{patient}', [ApiController::class, 'patientShow']);
    Route::put('/patients/{patient}', [ApiController::class, 'patientUpdate']);
    Route::delete('/patients/{patient}', [ApiController::class, 'patientDestroy']);

    // Medecins
    Route::get('/medecins', [ApiController::class, 'medecins']);
    Route::get('/medecins/{medecin}', [ApiController::class, 'medecinShow']);

    // Consultations
    Route::get('/consultations', [ApiController::class, 'consultations']);
    Route::get('/consultations/{consultation}', [ApiController::class, 'consultationShow']);

    // Medicaments
    Route::get('/medicaments', [ApiController::class, 'medicaments']);

    // Factures
    Route::get('/factures', [ApiController::class, 'factures']);
});
