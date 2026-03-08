<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Hospitalisation;
use App\Models\Transaction;
use App\Models\Medecin;
use App\Models\Chambre;
use App\Models\Medicament;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $stats = null;

        if ($user->role === 'admin') {
            $medecins       = Medecin::all();
            $chambres       = Chambre::all();
            $chambresOcc    = $chambres->where('statut', 'occupee')->count();
            $chambresTotal  = $chambres->count();

            $stats = [
                'patients_total'         => Patient::count(),
                'patients_hospitalises'  => Hospitalisation::where('statut', 'en_cours')->count(),
                'consultations_jour'     => Consultation::whereDate('date', today())->count(),
                'consultations_attente'  => Consultation::where('statut', 'en_attente')->whereDate('date', today())->count(),
                'medecins_total'         => $medecins->count(),
                'medecins_disponibles'   => $medecins->where('statut', 'disponible')->count(),
                'recettes_jour'          => Transaction::whereDate('date', today())->where('type', 'entree')->sum('montant'),
                'factures_impayees'      => Facture::where('statut', 'en_attente')->sum('montant'),
                'chambres_occupees'      => $chambresOcc,
                'chambres_total'         => $chambresTotal,
                'occupation'             => $chambresTotal > 0 ? round(($chambresOcc / $chambresTotal) * 100) : 0,
            ];
        }

        return view('dashboard', compact('user', 'stats'));
    }
}
