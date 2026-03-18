<?php

namespace App\Http\Controllers\Pharmacie;

use App\Http\Controllers\Controller;
use App\Helpers\AuditHelper;
use App\Models\Ordonnance;
use App\Models\Medicament;
use App\Models\MouvementStock;
use App\Models\FicheApprovisionnement;
use App\Notifications\StockBas;
use Illuminate\Http\Request;

class PharmacieController extends Controller
{
    public function index(Request $request)
    {
        // Médicaments filtrés / paginés pour la table stock
        $query = Medicament::query();
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        $medicamentsPagines = $query->orderBy('nom')->paginate(15)->withQueryString();

        // Données globales (stats + sélects)
        $tousLesMedicaments = Medicament::orderBy('nom')->get();
        $categories = Medicament::select('categorie')->distinct()->whereNotNull('categorie')->orderBy('categorie')->pluck('categorie');
        $medicamentsStockBas = $tousLesMedicaments->filter(fn($m) => $m->stock <= $m->stock_min);

        $ordonnancesEnAttente = Ordonnance::with(['patient'])
            ->where('statut_dispensation', 'en_attente')
            ->orderBy('date', 'desc')
            ->get();

        $ordonnancesPreparees = Ordonnance::with(['patient'])
            ->where('statut_dispensation', 'prepare')
            ->orderBy('date', 'desc')
            ->get();

        $stats = [
            'total_medicaments' => $tousLesMedicaments->count(),
            'en_attente'        => $ordonnancesEnAttente->count(),
            'preparees'         => $ordonnancesPreparees->count(),
            'stock_bas'         => $medicamentsStockBas->count(),
            'valeur_stock'      => $tousLesMedicaments->sum(fn($m) => $m->stock * $m->prix_unitaire),
        ];

        // Top 5 medicaments by stock level
        $topMedicaments = \App\Models\Medicament::orderBy('stock', 'desc')->limit(5)->get(['nom', 'stock', 'stock_min']);

        // Mouvements last 7 days
        $mouvDays = collect(range(6, 0))->map(fn($i) => now()->subDays($i));
        $mouvementsParJour = $mouvDays->map(fn($d) => [
            'date' => $d->locale('fr')->isoFormat('ddd D'),
            'entrees' => \App\Models\MouvementStock::where('type', 'entree')->whereDate('date', $d->toDateString())->sum('quantite'),
            'sorties' => \App\Models\MouvementStock::where('type', 'sortie')->whereDate('date', $d->toDateString())->sum('quantite'),
        ]);

        $useCharts = true;

        return view('pharmacie.index', compact(
            'medicamentsPagines', 'tousLesMedicaments', 'categories',
            'medicamentsStockBas', 'ordonnancesEnAttente', 'ordonnancesPreparees', 'stats',
            'topMedicaments', 'mouvementsParJour', 'useCharts'
        ));
    }

    public function storeMedicament(Request $request)
    {
        $validated = $request->validate([
            'nom'          => 'required|string|max:100',
            'categorie'    => 'nullable|string|max:100',
            'forme'        => 'nullable|string|max:50',
            'dosage'       => 'nullable|string|max:50',
            'stock'        => 'required|integer|min:0',
            'stock_min'    => 'required|integer|min:0',
            'prix_unitaire'=> 'required|integer|min:0',
            'fournisseur'  => 'nullable|string|max:100',
        ]);

        Medicament::create($validated);

        return redirect()->back()->with('success', 'Médicament ajouté avec succès');
    }

    public function storeMouvement(Request $request)
    {
        $validated = $request->validate([
            'medicament_id' => 'required|exists:medicaments,id',
            'type'          => 'required|in:entree,sortie',
            'quantite'      => 'required|integer|min:1',
            'motif'         => 'nullable|string|max:255',
        ]);

        $medicament = Medicament::findOrFail($validated['medicament_id']);

        // Vérifier que le stock ne devient pas négatif pour les sorties
        if ($validated['type'] === 'sortie' && $medicament->stock < $validated['quantite']) {
            return redirect()->back()->with('error', 'Stock insuffisant. Stock actuel : ' . $medicament->stock . ' unités.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $medicament) {
                if ($validated['type'] === 'entree') {
                    $medicament->increment('stock', $validated['quantite']);
                } else {
                    $medicament->decrement('stock', $validated['quantite']);
                }

                MouvementStock::create([
                    'medicament_id' => $validated['medicament_id'],
                    'type'          => $validated['type'],
                    'quantite'      => $validated['quantite'],
                    'date'          => now(),
                    'motif'         => $validated['motif'] ?? '',
                ]);
            });

            AuditHelper::log('create', 'Mouvement stock: ' . $validated['type'] . ' de ' . $validated['quantite'] . ' unités');

            // Vérifier si le stock est bas après le mouvement
            $medicament->refresh();
            if ($medicament->stock <= $medicament->stock_min) {
                \App\Models\User::where('role', 'pharmacie')->each(fn($u) => $u->notify(new StockBas($medicament)));
            }

            return redirect()->back()->with('success', 'Mouvement de stock enregistré');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function stock()
    {
        $medicaments = Medicament::orderBy('nom')->paginate(20)->appends(request()->query());

        return view('pharmacie.stock.index', compact('medicaments'));
    }

    public function demandes()
    {
        $ordonnances = Ordonnance::with(['patient', 'medecin', 'medicaments'])
            ->orderBy('date', 'desc')
            ->get();

        return view('pharmacie.demandes', compact('ordonnances'));
    }

    public function alertes()
    {
        $medicaments = Medicament::all();
        $alertes = $medicaments->filter(function($m) {
            return $m->stock <= $m->stock_min;
        });

        return view('pharmacie.alertes', compact('alertes'));
    }

    public function mouvements()
    {
        $mouvements = MouvementStock::with('medicament')
            ->orderBy('date', 'desc')
            ->get();

        return view('pharmacie.mouvements', compact('mouvements'));
    }

    public function updateMedicament(Request $request, Medicament $medicament)
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:100',
            'forme'         => 'nullable|string|max:50',
            'dosage'        => 'nullable|string|max:50',
            'stock_min'     => 'required|integer|min:0',
            'prix_unitaire' => 'required|integer|min:0',
            'categorie'     => 'nullable|string|max:100',
            'fournisseur'   => 'nullable|string|max:100',
        ]);

        $medicament->update($validated);

        return redirect()->back()->with('success', 'Médicament mis à jour avec succès.');
    }

    public function destroyMedicament(Medicament $medicament)
    {
        // Vérifier si le médicament est référencé dans des ordonnances actives
        $inOrdonnances = Ordonnance::where('statut_dispensation', 'en_attente')
            ->whereHas('medicaments', function ($q) use ($medicament) {
                $q->where('nom', $medicament->nom);
            })
            ->exists();

        if ($inOrdonnances) {
            return redirect()->back()->with('error', 'Impossible de supprimer ce médicament : il est référencé dans des ordonnances en attente.');
        }

        $medicament->delete();

        return redirect()->back()->with('success', 'Médicament supprimé avec succès.');
    }

    public function approvisionnements()
    {
        $approvisionnements = FicheApprovisionnement::with('lignes.medicament')
            ->orderBy('date', 'desc')
            ->get();

        $medicaments = Medicament::orderBy('nom')->get();

        return view('pharmacie.approvisionnements', compact('approvisionnements', 'medicaments'));
    }
}
