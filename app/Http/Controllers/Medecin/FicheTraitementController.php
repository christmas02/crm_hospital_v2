<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\FicheTraitement;
use App\Models\Consultation;
use App\Models\ActeMedical;
use App\Models\Facture;
use App\Models\FactureLigne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FicheTraitementController extends Controller
{
    public function store(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'diagnostic'   => 'required|string',
            'observations' => 'nullable|string',
            'actes'        => 'nullable|array',
            'actes.*.id'   => 'required_with:actes|exists:actes_medicaux,id',
            'actes.*.quantite' => 'required_with:actes|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $consultation) {
            $totalFacturable = 0;
            $acteData = [];

            foreach ($validated['actes'] ?? [] as $acte) {
                $acteMedical = ActeMedical::find($acte['id']);
                $subtotal = $acteMedical->prix * $acte['quantite'];

                if ($acteMedical->facturable) {
                    $totalFacturable += $subtotal;
                }

                $acteData[$acte['id']] = [
                    'nom'        => $acteMedical->nom,
                    'prix'       => $acteMedical->prix,
                    'quantite'   => $acte['quantite'],
                    'facturable' => $acteMedical->facturable,
                ];
            }

            $fiche = FicheTraitement::updateOrCreate(
                ['consultation_id' => $consultation->id],
                [
                    'patient_id'       => $consultation->patient_id,
                    'medecin_id'       => $consultation->medecin_id,
                    'date'             => now(),
                    'diagnostic'       => $validated['diagnostic'],
                    'observations'     => $validated['observations'] ?? null,
                    'total_facturable' => $totalFacturable,
                ]
            );

            if (!empty($acteData)) {
                $fiche->actes()->sync($acteData);
            }

            // Créer ou mettre à jour la facture
            if ($totalFacturable > 0 && !$consultation->facture()->exists()) {
                $numero = 'FAC-' . date('Y') . '-' . str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT);

                $facture = Facture::create([
                    'numero'              => $numero,
                    'patient_id'          => $consultation->patient_id,
                    'consultation_id'     => $consultation->id,
                    'fiche_traitement_id' => $fiche->id,
                    'date'                => now(),
                    'montant'             => $totalFacturable,
                    'statut'              => 'en_attente',
                    'envoye_par'          => 'medecin',
                ]);

                foreach ($acteData as $id => $data) {
                    if ($data['facturable']) {
                        FactureLigne::create([
                            'facture_id'    => $facture->id,
                            'description'   => $data['nom'],
                            'quantite'      => $data['quantite'],
                            'prix_unitaire' => $data['prix'],
                            'total'         => $data['prix'] * $data['quantite'],
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Fiche de traitement enregistrée');
    }
}
