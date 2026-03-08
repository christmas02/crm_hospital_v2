<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Medecin;
use App\Models\Facture;
use App\Models\Rendezvous;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReceptionController extends Controller
{
    public function index()
    {
        // Stats comme dans la source originale
        $stats = [
            'patients_aujourdhui' => Consultation::whereDate('date', today())->distinct('patient_id')->count('patient_id'),
            'en_attente' => Consultation::where('statut', 'en_attente')->whereDate('date', today())->count(),
            'factures_envoyees' => Facture::where('statut', 'envoyee')->count(),
            'en_attente_paiement' => Facture::whereIn('statut', ['en_attente', 'envoyee'])->count(),
        ];

        $consultationsEnAttente = Consultation::with(['patient', 'medecin'])
            ->whereDate('date', today())
            ->where('statut', 'en_attente')
            ->orderBy('heure')
            ->get();

        $derniersPatients = Patient::orderBy('date_inscription', 'desc')
            ->limit(10)
            ->get();

        $patients = Patient::orderBy('nom')->get();
        $medecins = Medecin::where('statut', '!=', 'absent')->orderBy('nom')->get();

        return view('reception.index', compact('stats', 'consultationsEnAttente', 'derniersPatients', 'patients', 'medecins'));
    }

    /**
     * Afficher les factures pour la réception
     */
    public function factures(Request $request)
    {
        $query = Facture::with(['patient', 'consultation']);

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $factures = $query->orderBy('created_at', 'desc')->paginate(20);
        $factures->appends($request->query());

        return view('reception.factures.index', compact('factures'));
    }
}
