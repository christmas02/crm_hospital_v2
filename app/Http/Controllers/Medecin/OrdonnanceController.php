<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Ordonnance;
use App\Models\OrdonnanceMedicament;
use App\Models\Medicament;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdonnanceController extends Controller
{
    public function store(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'recommandations'           => 'nullable|string',
            'medicaments'               => 'required|array|min:1',
            'medicaments.*.id'          => 'required|exists:medicaments,id',
            'medicaments.*.posologie'   => 'required|string',
            'medicaments.*.duree'       => 'required|string',
            'medicaments.*.quantite'    => 'required|integer|min:1',
        ]);

        // Check for allergy alerts
        $consultation = \App\Models\Consultation::with('patient')->find($consultation->id);
        $patient = $consultation->patient;
        $allergies = is_array($patient->allergies) ? $patient->allergies : [];

        if (!empty($allergies)) {
            $allergiesLower = array_map('strtolower', array_map('trim', $allergies));
            foreach ($validated['medicaments'] as $med) {
                $medicament = \App\Models\Medicament::find($med['id']);
                if ($medicament) {
                    $medNom = strtolower($medicament->nom);
                    $medCategorie = strtolower($medicament->categorie ?? '');
                    foreach ($allergiesLower as $allergie) {
                        if (str_contains($medNom, $allergie) || str_contains($medCategorie, $allergie) || str_contains($allergie, $medNom)) {
                            return redirect()->back()
                                ->with('error', '⚠️ ALERTE ALLERGIE : Le patient est allergique à "' . $allergie . '". Le médicament "' . $medicament->nom . '" pourrait être dangereux.')
                                ->withInput();
                        }
                    }
                }
            }
        }

        $ordonnance = DB::transaction(function () use ($validated, $consultation) {
            $numero = 'ORD-' . date('Y') . '-' . str_pad(Ordonnance::count() + 1, 4, '0', STR_PAD_LEFT);

            $ordonnance = Ordonnance::updateOrCreate(
                ['consultation_id' => $consultation->id],
                [
                    'patient_id'          => $consultation->patient_id,
                    'medecin_id'          => $consultation->medecin_id,
                    'date'                => now(),
                    'numero_retrait'      => $numero,
                    'statut_dispensation' => 'en_attente',
                    'recommandations'     => $validated['recommandations'],
                ]
            );

            // Remplacer les médicaments
            $ordonnance->medicaments()->delete();

            foreach ($validated['medicaments'] as $med) {
                $medicament = Medicament::find($med['id']);

                OrdonnanceMedicament::create([
                    'ordonnance_id' => $ordonnance->id,
                    'nom'           => $medicament->nom,
                    'posologie'     => $med['posologie'],
                    'duree'         => $med['duree'],
                    'quantite'      => $med['quantite'],
                ]);
            }

            return $ordonnance;
        });

        // Notify pharmacist
        \App\Models\User::where('role', 'pharmacie')->each(function($u) use ($ordonnance) {
            $u->notify(new \App\Notifications\NouvelleOrdonnance($ordonnance));
        });

        return redirect()->back()->with('success', 'Ordonnance créée avec succès');
    }

    public function showJson(Ordonnance $ordonnance)
    {
        $ordonnance->load(['patient', 'medecin', 'medicaments']);
        return response()->json([
            'id' => $ordonnance->id,
            'consultation_id' => $ordonnance->consultation_id,
            'patient_id' => $ordonnance->patient_id,
            'patient_nom' => $ordonnance->patient->prenom . ' ' . $ordonnance->patient->nom,
            'medecin_id' => $ordonnance->medecin_id,
            'medecin_nom' => 'Dr. ' . $ordonnance->medecin->prenom . ' ' . $ordonnance->medecin->nom,
            'date' => $ordonnance->date->format('Y-m-d'),
            'numero_retrait' => $ordonnance->numero_retrait,
            'statut_dispensation' => $ordonnance->statut_dispensation,
            'recommandations' => $ordonnance->recommandations,
            'medicaments' => $ordonnance->medicaments->map(function ($med) {
                return [
                    'id' => $med->id,
                    'nom' => $med->nom,
                    'posologie' => $med->posologie,
                    'duree' => $med->duree,
                    'quantite' => $med->quantite,
                ];
            }),
        ]);
    }

    public function update(Request $request, Ordonnance $ordonnance)
    {
        if ($ordonnance->statut_dispensation === 'remis') {
            return redirect()->back()->with('error', 'Impossible de modifier une ordonnance déjà dispensée.');
        }

        $validated = $request->validate([
            'recommandations'           => 'nullable|string',
            'medicaments'               => 'required|array|min:1',
            'medicaments.*.nom'         => 'required|string',
            'medicaments.*.posologie'   => 'required|string',
            'medicaments.*.duree'       => 'required|string',
            'medicaments.*.quantite'    => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $ordonnance) {
            $ordonnance->update([
                'recommandations' => $validated['recommandations'],
            ]);

            // Remplacer les médicaments
            $ordonnance->medicaments()->delete();

            foreach ($validated['medicaments'] as $med) {
                OrdonnanceMedicament::create([
                    'ordonnance_id' => $ordonnance->id,
                    'nom'           => $med['nom'],
                    'posologie'     => $med['posologie'],
                    'duree'         => $med['duree'],
                    'quantite'      => $med['quantite'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Ordonnance mise à jour avec succès');
    }

    public function pdf(Ordonnance $ordonnance)
    {
        $ordonnance->load(['patient', 'medecin', 'medicaments']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medecin.ordonnance-pdf', compact('ordonnance'));
        return $pdf->stream('ordonnance-' . $ordonnance->id . '.pdf');
    }

    public function destroy(Ordonnance $ordonnance)
    {
        // Ne pas supprimer si déjà dispensée
        if ($ordonnance->statut_dispensation === 'remis') {
            return redirect()->back()->with('error', 'Impossible de supprimer une ordonnance déjà dispensée.');
        }

        // Supprimer les médicaments de l'ordonnance
        $ordonnance->medicaments()->delete();

        $ordonnance->delete();

        return redirect()->back()->with('success', 'Ordonnance supprimée avec succès.');
    }
}
