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

        $consultations = $query->orderBy('heure')->paginate(20)->appends($request->query());

        $patients = \App\Models\Patient::orderBy('nom')->get();
        $medecins = \App\Models\Medecin::where('statut', '!=', 'absent')->orderBy('nom')->get();

        return view('reception.consultations.index', compact('consultations', 'patients', 'medecins'));
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
            'date' => 'required|date|after_or_equal:today',
            'heure' => 'required',
            'motif' => 'required|string|max:500',
        ]);

        $validated['statut'] = 'en_attente';
        $consultation = Consultation::create($validated);

        // Auto-create file d'attente entry
        \App\Models\FileAttente::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $consultation->patient_id,
            'medecin_id' => $consultation->medecin_id,
            'heure_arrivee' => $validated['heure'],
            'position' => \App\Models\FileAttente::where('medecin_id', $consultation->medecin_id)->where('statut', 'en_attente')->count() + 1,
            'statut' => 'en_attente',
        ]);

        // Send confirmation email to patient if email exists
        $consultation->load(['patient', 'medecin']);
        if ($consultation->patient->email) {
            try {
                $consultation->patient->notify(new \App\Notifications\ConfirmationRendezvous($consultation));
            } catch (\Exception $e) {
                // Don't block if email fails
            }
        }

        return redirect()->route('reception.consultations.index')
            ->with('success', 'Consultation enregistrée');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin', 'ficheTraitement', 'ordonnance', 'facture']);
        return view('reception.consultations.show', compact('consultation'));
    }

    public function showJson(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin']);
        return response()->json([
            'id' => $consultation->id,
            'patient_id' => $consultation->patient_id,
            'patient_nom' => $consultation->patient->prenom . ' ' . $consultation->patient->nom,
            'medecin_id' => $consultation->medecin_id,
            'medecin_nom' => 'Dr. ' . $consultation->medecin->prenom . ' ' . $consultation->medecin->nom,
            'date' => $consultation->date->format('Y-m-d'),
            'heure' => $consultation->heure,
            'motif' => $consultation->motif,
            'statut' => $consultation->statut,
        ]);
    }

    public function edit(Consultation $consultation)
    {
        $patients = Patient::orderBy('nom')->get();
        $medecins = Medecin::where('statut', '!=', 'absent')->orderBy('nom')->get();
        return view('reception.consultations.edit', compact('consultation', 'patients', 'medecins'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'date' => 'required|date|after_or_equal:today',
            'heure' => 'required',
            'motif' => 'required|string',
        ]);

        $consultation->update($validated);

        return redirect()->route('reception.consultations.index')
            ->with('success', 'Consultation mise à jour avec succès');
    }

    public function envoyerRappel(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin']);

        if (!$consultation->patient->email) {
            return redirect()->back()->with('error', 'Ce patient n\'a pas d\'adresse email.');
        }

        try {
            $consultation->patient->notify(new \App\Notifications\RappelRendezvous($consultation));

            // Notify current user too
            auth()->user()->notify(new \App\Notifications\RappelRendezvous($consultation));

            return redirect()->back()->with('success', 'Rappel envoyé par email à ' . $consultation->patient->prenom . ' ' . $consultation->patient->nom);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi: ' . $e->getMessage());
        }
    }

    public function destroy(Consultation $consultation)
    {
        if ($consultation->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les consultations en attente peuvent être supprimées.');
        }

        // Supprimer l'entrée de la file d'attente associée
        FileAttente::where('consultation_id', $consultation->id)->delete();

        $consultation->delete();

        return redirect()->back()->with('success', 'Consultation supprimée avec succès.');
    }
}
