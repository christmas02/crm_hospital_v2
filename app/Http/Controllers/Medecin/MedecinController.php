<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\FicheTraitement;
use App\Models\FileAttente;
use App\Models\Medecin;
use App\Models\Ordonnance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MedecinController extends Controller
{
    /**
     * Obtenir le médecin courant (pour demo, premier médecin)
     */
    protected function getMedecin()
    {
        return Medecin::first();
    }

    /**
     * Tableau de bord médecin
     */
    public function index()
    {
        $medecin = $this->getMedecin();

        $consultationsEnAttente = Consultation::with(['patient', 'medecin'])
            ->where('medecin_id', $medecin->id)
            ->whereDate('date', today())
            ->where('statut', 'en_attente')
            ->orderBy('heure')
            ->get();

        $consultationEnCours = Consultation::with(['patient', 'medecin'])
            ->where('medecin_id', $medecin->id)
            ->where('statut', 'en_cours')
            ->first();

        $consultationsTerminees = Consultation::with(['patient', 'ficheTraitement'])
            ->where('medecin_id', $medecin->id)
            ->whereDate('date', today())
            ->where('statut', 'termine')
            ->orderBy('heure', 'desc')
            ->get();

        $ordonnancesCount = Ordonnance::where('medecin_id', $medecin->id)
            ->whereDate('date', today())
            ->count();

        $stats = [
            'en_attente' => $consultationsEnAttente->count(),
            'en_cours' => $consultationEnCours ? 1 : 0,
            'terminees_jour' => $consultationsTerminees->count(),
            'total_semaine' => Consultation::where('medecin_id', $medecin->id)
                ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
            'ordonnances_jour' => $ordonnancesCount,
        ];

        return view('medecin.index', compact('medecin', 'consultationsEnAttente', 'consultationEnCours', 'consultationsTerminees', 'stats'));
    }

    /**
     * File d'attente détaillée
     */
    public function fileAttente()
    {
        $medecin = $this->getMedecin();

        $consultations = Consultation::with(['patient'])
            ->where('medecin_id', $medecin->id)
            ->whereDate('date', today())
            ->where('statut', 'en_attente')
            ->orderBy('heure')
            ->get();

        $consultationEnCours = Consultation::with(['patient'])
            ->where('medecin_id', $medecin->id)
            ->where('statut', 'en_cours')
            ->first();

        return view('medecin.file-attente', compact('medecin', 'consultations', 'consultationEnCours'));
    }

    /**
     * Dossiers médicaux
     */
    public function dossiers(Request $request)
    {
        $medecin = $this->getMedecin();

        $patients = Patient::orderBy('nom')->orderBy('prenom')->get();

        $dossier = null;
        $patient = null;

        if ($request->filled('patient_id')) {
            $patient = Patient::with([
                'dossierMedical',
                'consultations' => function($q) {
                    $q->with(['medecin', 'ficheTraitement'])->orderBy('date', 'desc');
                },
                'hospitalisations.chambre',
                'ordonnances',
            ])->find($request->patient_id);

            if ($patient) {
                $dossier = $patient->dossierMedical;
            }
        }

        return view('medecin.dossiers', compact('medecin', 'patients', 'patient', 'dossier'));
    }

    /**
     * Liste des fiches de traitement
     */
    public function fichesTraitement()
    {
        $medecin = $this->getMedecin();

        $fiches = FicheTraitement::with(['patient', 'consultation', 'actesMedicaux'])
            ->where('medecin_id', $medecin->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('medecin.fiches', compact('medecin', 'fiches'));
    }

    /**
     * Liste des ordonnances
     */
    public function ordonnances()
    {
        $medecin = $this->getMedecin();

        $ordonnances = Ordonnance::with(['patient', 'medicaments'])
            ->where('medecin_id', $medecin->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('medecin.ordonnances', compact('medecin', 'ordonnances'));
    }
}
