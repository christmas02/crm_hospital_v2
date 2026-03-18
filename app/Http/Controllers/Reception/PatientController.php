<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\DossierMedical;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('nom')->paginate(20);
        return view('reception.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('reception.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:500',
            'groupe_sanguin' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
        ]);

        $validated['date_inscription'] = now();
        $validated['statut'] = 'actif';

        // Convertir les allergies en tableau si c'est une chaîne
        if (isset($validated['allergies']) && is_string($validated['allergies'])) {
            $validated['allergies'] = array_filter(array_map('trim', explode(',', $validated['allergies'])));
        } else {
            $validated['allergies'] = [];
        }

        $patient = Patient::create($validated);

        // Créer un dossier médical vide
        DossierMedical::create([
            'patient_id' => $patient->id,
            'antecedents' => [],
            'maladies_chroniques' => [],
            'chirurgies' => [],
            'notes' => '',
        ]);

        // Si l'option "créer consultation directement" est cochée
        if ($request->has('creer_consultation') && $request->creer_consultation) {
            return redirect()->route('reception.consultations.create', ['patient_id' => $patient->id])
                ->with('success', 'Patient enregistré. Créez maintenant la consultation.');
        }

        return redirect()->route('reception.patients.show', $patient)
            ->with('success', 'Patient enregistré avec succès');
    }

    public function show(Patient $patient)
    {
        $patient->load(['dossierMedical', 'consultations.medecin', 'hospitalisations']);
        return view('reception.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('reception.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:500',
            'groupe_sanguin' => 'nullable|string|max:5',
            'statut' => 'nullable|in:actif,hospitalise,inactif',
            'allergies' => 'nullable|string',
        ]);

        // Convertir les allergies en tableau si c'est une chaîne
        if (isset($validated['allergies']) && is_string($validated['allergies'])) {
            $validated['allergies'] = array_filter(array_map('trim', explode(',', $validated['allergies'])));
        } else {
            $validated['allergies'] = [];
        }

        $patient->update($validated);

        return redirect()->route('reception.patients.show', $patient)
            ->with('success', 'Patient mis à jour avec succès');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $patients = Patient::where('nom', 'like', "%{$query}%")
            ->orWhere('prenom', 'like', "%{$query}%")
            ->orWhere('telephone', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($patients);
    }
}
