<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\FileAttente;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $query = Consultation::with(['patient', 'medecin']);

        // Filtrer par date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
        }

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $consultations = $query->orderBy('heure')->paginate(20);
        $consultations->appends($request->query());

        return view('reception.consultations.index', compact('consultations'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nom')->get();
        $medecins = Medecin::where('statut', '!=', 'absent')->get();

        return view('reception.consultations.create', compact('patients', 'medecins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'date' => 'required|date',
            'heure' => 'required',
            'motif' => 'required|string|max:500',
        ]);

        $validated['statut'] = 'en_attente';
        $consultation = Consultation::create($validated);

        // Ajouter à la file d'attente
        $position = FileAttente::where('medecin_id', $validated['medecin_id'])
            ->whereDate('created_at', today())
            ->count() + 1;

        FileAttente::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $validated['patient_id'],
            'medecin_id' => $validated['medecin_id'],
            'heure_arrivee' => now()->format('H:i'),
            'position' => $position,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('reception.consultations.index')
            ->with('success', 'Consultation enregistrée');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin', 'ficheTraitement', 'ordonnance', 'facture']);
        return view('reception.consultations.show', compact('consultation'));
    }
}
