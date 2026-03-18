<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDocument;
use App\Models\DossierMedical;
use App\Models\Medecin;
use App\Models\Vaccination;
use App\Helpers\AuditHelper;
use App\Notifications\NouveauPatient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%")->orWhere('telephone', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $patients = $query->orderBy('nom')->paginate(20)->appends($request->query());
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
            'email' => 'nullable|email|max:255|unique:patients,email',
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

        // Notifier les utilisateurs réception
        \App\Models\User::where('role', 'reception')->each(fn($u) => $u->notify(new NouveauPatient($patient)));

        AuditHelper::log('create', 'Patient créé: ' . $patient->prenom . ' ' . $patient->nom, $patient);

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
        $medecinsDisponibles = Medecin::where('statut', '!=', 'absent')->get();
        return view('reception.patients.show', compact('patient', 'medecinsDisponibles'));
    }

    public function showJson(Patient $patient)
    {
        $patient->load(['consultations.medecin']);

        return response()->json([
            'id' => $patient->id,
            'nom' => $patient->nom,
            'prenom' => $patient->prenom,
            'initiales' => strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)),
            'age' => \Carbon\Carbon::parse($patient->date_naissance)->age,
            'sexe' => $patient->sexe,
            'sexe_label' => $patient->sexe == 'M' ? 'Masculin' : 'Féminin',
            'date_naissance' => \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y'),
            'date_naissance_raw' => \Carbon\Carbon::parse($patient->date_naissance)->format('Y-m-d'),
            'telephone' => $patient->telephone ?? 'Non renseigné',
            'telephone_raw' => $patient->telephone ?? '',
            'email' => $patient->email ?? 'Non renseigné',
            'email_raw' => $patient->email ?? '',
            'adresse' => $patient->adresse ?? 'Non renseigné',
            'adresse_raw' => $patient->adresse ?? '',
            'groupe_sanguin' => $patient->groupe_sanguin,
            'allergies' => is_array($patient->allergies) ? $patient->allergies : ($patient->allergies ? explode(',', $patient->allergies) : []),
            'statut' => $patient->statut,
            'date_inscription' => $patient->date_inscription->format('d/m/Y'),
            'consultations' => $patient->consultations->sortByDesc('date')->take(5)->map(function ($c) {
                return [
                    'date' => $c->date->format('d/m/Y'),
                    'heure' => $c->heure,
                    'medecin' => 'Dr. ' . $c->medecin->nom,
                    'motif' => $c->motif,
                    'statut' => $c->statut,
                ];
            })->values(),
            'nb_consultations' => $patient->consultations->count(),
            'show_url' => route('reception.patients.show', $patient),
            'edit_url' => route('reception.patients.edit', $patient),
        ]);
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
            'email' => 'nullable|email|max:255|unique:patients,email,' . $patient->id,
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

    public function destroy(Patient $patient)
    {
        // Vérifier si le patient a une hospitalisation en cours
        if ($patient->hospitalisations()->where('statut', 'en_cours')->exists()) {
            return redirect()->back()->with('error', 'Impossible de supprimer un patient avec une hospitalisation en cours.');
        }

        // Vérifier si le patient a des consultations en cours
        if ($patient->consultations()->where('statut', 'en_cours')->exists()) {
            return redirect()->back()->with('error', 'Impossible de supprimer un patient avec une consultation en cours.');
        }

        AuditHelper::log('delete', 'Patient supprimé: ' . $patient->prenom . ' ' . $patient->nom, $patient);

        $patient->delete();

        return redirect()->back()->with('success', 'Patient supprimé avec succès.');
    }

    public function storeDocument(Request $request, Patient $patient)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:resultat_labo,radio,certificat,autre',
            'fichier' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
            'notes' => 'nullable|string',
        ]);

        $path = $request->file('fichier')->store('patients/' . $patient->id . '/documents', 'public');

        PatientDocument::create([
            'patient_id' => $patient->id,
            'nom' => $request->nom,
            'type' => $request->type,
            'fichier' => $path,
            'notes' => $request->notes,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Document ajouté avec succès');
    }

    public function destroyDocument(Patient $patient, PatientDocument $document)
    {
        \Storage::disk('public')->delete($document->fichier);
        $document->delete();
        return redirect()->back()->with('success', 'Document supprimé');
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

    public function storeVaccination(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'vaccin' => 'required|string|max:255',
            'maladie' => 'required|string|max:255',
            'date_administration' => 'required|date',
            'dose' => 'nullable|string|max:50',
            'lot' => 'nullable|string|max:50',
            'site_injection' => 'nullable|string|max:100',
            'prochain_rappel' => 'nullable|date|after:date_administration',
            'notes' => 'nullable|string',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['administre_par'] = auth()->id();

        Vaccination::create($validated);
        return redirect()->back()->with('success', 'Vaccination enregistrée');
    }

    public function carnetSante(Patient $patient)
    {
        $patient->load([
            'signesVitaux' => fn($q) => $q->orderBy('created_at', 'desc'),
            'consultations' => fn($q) => $q->with('medecin')->orderBy('date', 'desc'),
            'vaccinations' => fn($q) => $q->orderBy('date_administration', 'desc'),
            'certificats' => fn($q) => $q->with('medecin')->orderBy('date_emission', 'desc'),
            'demandesLabo' => fn($q) => $q->with(['medecin', 'resultats.examen'])->orderBy('date_demande', 'desc'),
            'ordonnances' => fn($q) => $q->with(['medecin', 'medicaments'])->orderBy('created_at', 'desc'),
            'hospitalisations' => fn($q) => $q->with(['chambre', 'medecin'])->orderBy('date_admission', 'desc'),
            'documents' => fn($q) => $q->orderBy('created_at', 'desc'),
            'dossierMedical',
        ]);

        return view('reception.patients.carnet', compact('patient'));
    }

    public function carnetSantePdf(Patient $patient)
    {
        $patient->load([
            'signesVitaux' => fn($q) => $q->orderBy('created_at', 'desc')->limit(10),
            'consultations' => fn($q) => $q->with('medecin')->orderBy('date', 'desc'),
            'vaccinations' => fn($q) => $q->orderBy('date_administration', 'desc'),
            'ordonnances' => fn($q) => $q->with(['medecin', 'medicaments'])->orderBy('created_at', 'desc'),
            'dossierMedical',
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reception.patients.carnet-pdf', compact('patient'));
        return $pdf->stream('carnet-sante-' . $patient->nom . '-' . $patient->prenom . '.pdf');
    }
}
