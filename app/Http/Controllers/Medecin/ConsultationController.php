<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\FicheTraitement;
use App\Models\ActeMedical;
use App\Models\FileAttente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'medecin', 'ficheTraitement.actesMedicaux', 'ordonnance.medicaments']);
        $actesMedicaux = ActeMedical::orderBy('categorie')->orderBy('nom')->get();
        $medicaments = \App\Models\Medicament::orderBy('nom')->get();

        return view('medecin.consultation', compact('consultation', 'actesMedicaux', 'medicaments'));
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

        return redirect()->route('medecin.index')->with('success', 'Consultation terminée');
    }
}
