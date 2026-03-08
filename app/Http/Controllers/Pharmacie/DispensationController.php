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

        return redirect()->back()->with('success', 'Médicaments remis');
    }
}
