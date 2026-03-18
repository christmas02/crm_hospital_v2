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

        DB::transaction(function () use ($validated, $consultation) {
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
        });

        return redirect()->back()->with('success', 'Ordonnance créée avec succès');
    }
}
