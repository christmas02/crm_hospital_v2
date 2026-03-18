<?php

namespace App\Http\Controllers\Pharmacie;

use App\Http\Controllers\Controller;
use App\Models\Ordonnance;
use Illuminate\Http\Request;

class DispensationController extends Controller
{
    public function preparer(Ordonnance $ordonnance)
    {
        $ordonnance->update([
            'statut_dispensation' => 'prepare',
            'date_preparation' => now(),
        ]);

        return redirect()->back()->with('success', 'Ordonnance préparée');
    }

    public function remettre(Request $request, Ordonnance $ordonnance)
    {
        $validated = $request->validate([
            'remis_a' => 'required|string|max:255',
        ]);

        $ordonnance->update([
            'statut_dispensation' => 'remis',
            'date_remise' => now(),
            'remis_a' => $validated['remis_a'],
        ]);

        // Auto-decrement stock for each medication in the ordonnance
        $ordonnance->load('medicaments');
        foreach ($ordonnance->medicaments as $med) {
            $medicament = \App\Models\Medicament::find($med->medicament_id);
            if ($medicament && isset($med->quantite) && $med->quantite > 0) {
                $medicament->decrement('stock', $med->quantite);

                // Create movement record
                \App\Models\MouvementStock::create([
                    'medicament_id' => $medicament->id,
                    'type' => 'sortie',
                    'quantite' => $med->quantite,
                    'motif' => 'Dispensation ordonnance - Patient: ' . $ordonnance->patient->prenom . ' ' . $ordonnance->patient->nom,
                    'date' => now(),
                ]);

                // Check if stock is now below minimum
                if ($medicament->fresh()->stock <= $medicament->stock_min) {
                    \App\Models\User::where('role', 'pharmacie')->each(fn($u) => $u->notify(new \App\Notifications\StockBas($medicament->fresh())));
                }
            }
        }

        return redirect()->back()->with('success', 'Médicaments remis');
    }
}
