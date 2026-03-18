<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Consultation;
use App\Models\Medicament;
use App\Models\Facture;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // ===== PATIENTS =====
    public function patients(Request $request)
    {
        $query = Patient::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%")->orWhere('telephone', 'like', "%$s%"));
        }
        return response()->json($query->orderBy('nom')->paginate($request->get('per_page', 20)));
    }

    public function patientShow(Patient $patient)
    {
        $patient->load(['consultations.medecin', 'dossierMedical', 'documents']);
        return response()->json($patient);
    }

    public function patientStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:patients,email',
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string|max:5',
        ]);
        $validated['date_inscription'] = now();
        $validated['statut'] = 'actif';
        $patient = Patient::create($validated);
        return response()->json($patient, 201);
    }

    public function patientUpdate(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'date_naissance' => 'sometimes|date',
            'sexe' => 'sometimes|in:M,F',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:patients,email,' . $patient->id,
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string|max:5',
        ]);
        $patient->update($validated);
        return response()->json($patient);
    }

    public function patientDestroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(['message' => 'Patient supprimé'], 200);
    }

    // ===== MEDECINS =====
    public function medecins(Request $request)
    {
        $query = Medecin::withCount('consultations');
        if ($request->filled('specialite')) $query->where('specialite', $request->specialite);
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        return response()->json($query->orderBy('nom')->get());
    }

    public function medecinShow(Medecin $medecin)
    {
        $medecin->loadCount(['consultations', 'hospitalisations']);
        return response()->json($medecin);
    }

    // ===== CONSULTATIONS =====
    public function consultations(Request $request)
    {
        $query = Consultation::with(['patient', 'medecin']);
        if ($request->filled('date')) $query->whereDate('date', $request->date);
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('medecin_id')) $query->where('medecin_id', $request->medecin_id);
        return response()->json($query->orderBy('date', 'desc')->paginate($request->get('per_page', 20)));
    }

    public function consultationShow(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin', 'ficheTraitement', 'ordonnance.medicaments', 'commentaires.user']);
        return response()->json($consultation);
    }

    // ===== MEDICAMENTS =====
    public function medicaments(Request $request)
    {
        $query = Medicament::query();
        if ($request->filled('search')) $query->where('nom', 'like', '%' . $request->search . '%');
        if ($request->filled('alerte')) $query->whereColumn('stock', '<=', 'stock_min');
        return response()->json($query->orderBy('nom')->get());
    }

    // ===== FACTURES =====
    public function factures(Request $request)
    {
        $query = Facture::with('patient');
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        return response()->json($query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 20)));
    }

    // ===== STATS =====
    public function stats()
    {
        return response()->json([
            'patients_total' => Patient::count(),
            'medecins_total' => Medecin::count(),
            'medecins_disponibles' => Medecin::where('statut', 'disponible')->count(),
            'consultations_jour' => Consultation::whereDate('date', today())->count(),
            'consultations_attente' => Consultation::where('statut', 'en_attente')->count(),
            'medicaments_alerte' => Medicament::whereColumn('stock', '<=', 'stock_min')->count(),
            'factures_impayees' => Facture::whereIn('statut', ['en_attente', 'envoyee'])->count(),
        ]);
    }
}
