<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationNote;
use App\Models\CertificatMedical;
use App\Models\FicheTraitement;
use App\Models\ActeMedical;
use App\Models\FileAttente;
use App\Models\Medecin;
use App\Models\SigneVital;
use App\Notifications\ConsultationTerminee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin', 'ficheTraitement.actesMedicaux', 'ordonnance.medicaments', 'commentaires.user', 'facture', 'certificats.medecin']);
        $actesMedicaux = ActeMedical::orderBy('categorie')->orderBy('nom')->get();
        $medicaments = \App\Models\Medicament::orderBy('nom')->get();
        $signesVitaux = SigneVital::where('patient_id', $consultation->patient_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $currentMedecin = $this->getMedecin();
        $medecinsDisponibles = Medecin::where('id', '!=', $currentMedecin->id)->where('statut', 'actif')->orderBy('nom')->get();
        $references = \App\Models\Reference::with(['medecinCible'])
            ->where('consultation_id', $consultation->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('medecin.consultation', compact('consultation', 'actesMedicaux', 'medicaments', 'signesVitaux', 'medecinsDisponibles', 'references'));
    }

    public function start(Consultation $consultation)
    {
        $consultation->update(['statut' => 'en_cours']);

        FileAttente::where('consultation_id', $consultation->id)
            ->update(['statut' => 'appele']);

        return redirect()->route('medecin.consultations.show', $consultation);
    }

    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'diagnostic' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $consultation->update($validated);

        return redirect()->back()->with('success', 'Consultation mise à jour');
    }

    public function terminer(Request $request, Consultation $consultation)
    {
        $consultation->update([
            'statut' => 'termine',
            'diagnostic' => $request->diagnostic,
            'notes' => $request->notes,
        ]);

        FileAttente::where('consultation_id', $consultation->id)
            ->update(['statut' => 'termine']);

        // Notifier les utilisateurs réception
        $consultation->load('patient');
        \App\Models\User::where('role', 'reception')->each(fn($u) => $u->notify(new ConsultationTerminee($consultation)));

        return redirect()->route('medecin.index')->with('success', 'Consultation terminée');
    }

    public function storeNote(Request $request, Consultation $consultation)
    {
        $request->validate(['contenu' => 'required|string']);

        ConsultationNote::create([
            'consultation_id' => $consultation->id,
            'user_id' => auth()->id(),
            'contenu' => $request->contenu,
        ]);

        return redirect()->back()->with('success', 'Note ajoutée');
    }

    public function storeSignesVitaux(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'temperature' => 'nullable|numeric|between:30,45',
            'tension_systolique' => 'nullable|string|max:5',
            'tension_diastolique' => 'nullable|string|max:5',
            'pouls' => 'nullable|integer|between:20,250',
            'frequence_respiratoire' => 'nullable|integer|between:5,60',
            'saturation_o2' => 'nullable|integer|between:50,100',
            'poids' => 'nullable|numeric|between:0.5,500',
            'taille' => 'nullable|numeric|between:20,250',
            'glycemie' => 'nullable|integer|between:20,600',
            'notes' => 'nullable|string',
        ]);

        // Calculate IMC
        if (!empty($validated['poids']) && !empty($validated['taille'])) {
            $tailleM = $validated['taille'] / 100;
            $validated['imc'] = round($validated['poids'] / ($tailleM * $tailleM), 1);
        }

        $validated['patient_id'] = $consultation->patient_id;
        $validated['consultation_id'] = $consultation->id;
        $validated['pris_par'] = auth()->id();

        SigneVital::create($validated);

        // Check for abnormal values and alert
        $alerts = [];
        if (!empty($validated['temperature']) && ($validated['temperature'] > 39 || $validated['temperature'] < 36)) {
            $alerts[] = 'Température critique: ' . $validated['temperature'] . '°C';
        }
        if (!empty($validated['saturation_o2']) && $validated['saturation_o2'] < 92) {
            $alerts[] = 'Saturation O2 critique: ' . $validated['saturation_o2'] . '%';
        }
        if (!empty($validated['pouls']) && ($validated['pouls'] > 120 || $validated['pouls'] < 50)) {
            $alerts[] = 'Pouls anormal: ' . $validated['pouls'] . ' bpm';
        }
        if (!empty($validated['tension_systolique']) && (intval($validated['tension_systolique']) > 18 || intval($validated['tension_systolique']) < 9)) {
            $alerts[] = 'Tension systolique critique: ' . $validated['tension_systolique'];
        }

        if (!empty($alerts)) {
            // Notify the doctor
            $medecin = $consultation->medecin;
            if ($medecin->user) {
                $medecin->user->notify(new \App\Notifications\AlerteVitale($consultation->patient, $alerts));
            }
            // Notify admin users too
            \App\Models\User::where('role', 'admin')->each(fn($u) => $u->notify(new \App\Notifications\AlerteVitale($consultation->patient, $alerts)));

            return redirect()->back()->with('success', 'Signes vitaux enregistrés')->with('error', '⚠️ ALERTE: ' . implode(' | ', $alerts));
        }

        return redirect()->back()->with('success', 'Signes vitaux enregistrés');
    }

    private function getMedecin()
    {
        $medecin = auth()->user()->medecin;
        if (!$medecin) {
            $medecin = Medecin::first();
        }
        return $medecin;
    }

    public function storeReference(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'medecin_cible_id' => 'required|exists:medecins,id',
            'motif' => 'required|string',
            'contexte_clinique' => 'nullable|string',
            'urgence' => 'required|in:normal,urgent,tres_urgent',
        ]);

        $medecin = $this->getMedecin();
        $numero = 'REF-' . date('Ymd') . '-' . str_pad(\App\Models\Reference::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        \App\Models\Reference::create(array_merge($validated, [
            'numero' => $numero,
            'patient_id' => $consultation->patient_id,
            'medecin_referent_id' => $medecin->id,
            'consultation_id' => $consultation->id,
            'date_reference' => today(),
            'statut' => 'en_attente',
        ]));

        // Notify target doctor
        $medecinCible = \App\Models\Medecin::find($validated['medecin_cible_id']);
        if ($medecinCible->user) {
            $medecinCible->user->notify(new \App\Notifications\NouvelleReference($consultation->patient, $medecin));
        }

        return redirect()->back()->with('success', 'Référence ' . $numero . ' envoyée vers Dr. ' . $medecinCible->prenom . ' ' . $medecinCible->nom);
    }

    public function storeCertificat(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'type' => 'required|in:arret_maladie,aptitude,inaptitude,medical_general,deces',
            'motif' => 'required|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'nb_jours' => 'nullable|integer|min:1',
            'observations' => 'nullable|string',
            'conclusion' => 'nullable|string',
        ]);

        $numero = 'CERT-' . date('Ymd') . '-' . str_pad(CertificatMedical::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        $medecin = $this->getMedecin();

        CertificatMedical::create(array_merge($validated, [
            'numero' => $numero,
            'patient_id' => $consultation->patient_id,
            'medecin_id' => $medecin->id,
            'consultation_id' => $consultation->id,
            'date_emission' => today(),
        ]));

        return redirect()->back()->with('success', 'Certificat médical ' . $numero . ' créé');
    }

    public function certificatPdf(CertificatMedical $certificat)
    {
        $certificat->load(['patient', 'medecin']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medecin.certificat-pdf', compact('certificat'));
        return $pdf->stream('certificat-' . $certificat->numero . '.pdf');
    }
}
