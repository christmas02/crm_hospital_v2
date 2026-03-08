<?php

namespace App\Http\Controllers\Pharmacie;

use App\Http\Controllers\Controller;
use App\Models\Medicament;
use App\Models\MouvementStock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $medicaments = Medicament::orderBy('nom')->paginate(20);
        return view('pharmacie.stock.index', compact('medicaments'));
    }

    public function show(Medicament $medicament)
    {
        $mouvements = MouvementStock::where('medicament_id', $medicament->id)
            ->orderBy('date', 'desc')
            ->limit(20)
            ->get();

        return view('pharmacie.stock.show', compact('medicament', 'mouvements'));
    }

    public function ajuster(Request $request, Medicament $medicament)
    {
        $validated = $request->validate([
            'type' => 'required|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'motif' => 'required|string|max:255',
        ]);

        if ($validated['type'] === 'entree') {
            $medicament->increment('stock', $validated['quantite']);
        } else {
            $medicament->decrement('stock', $validated['quantite']);
        }

        MouvementStock::create([
            'medicament_id' => $medicament->id,
            'type' => $validated['type'],
            'quantite' => $validated['quantite'],
            'date' => now(),
            'motif' => $validated['motif'],
        ]);

        return redirect()->back()->with('success', 'Stock ajusté');
    }
}
