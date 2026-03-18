<?php

namespace App\Http\Controllers\Caisse;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Transaction;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CaisseController extends Controller
{
    public function index()
    {
        $facturesEnAttente = Facture::with(['patient', 'consultation', 'lignes'])
            ->where('statut', 'en_attente')
            ->orderBy('date', 'desc')
            ->get();

        $facturesPayees = Facture::with(['patient', 'lignes'])
            ->where('statut', 'payee')
            ->whereDate('date_paiement', today())
            ->get();

        $derniersPaiements = Paiement::with('patient')
            ->whereDate('date_paiement', today())
            ->orderBy('date_paiement', 'desc')
            ->limit(10)
            ->get();

        $dernieresTransactions = Transaction::orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'en_attente' => $facturesEnAttente->count(),
            'montant_attente' => $facturesEnAttente->sum(fn($f) => $f->montant_total),
            'encaissees_jour' => $facturesPayees->count(),
            'recettes_jour' => $facturesPayees->sum(fn($f) => $f->montant_total),
            'transactions_jour' => Transaction::whereDate('date', today())->count(),
        ];

        return view('caisse.index', compact('facturesEnAttente', 'facturesPayees', 'derniersPaiements', 'dernieresTransactions', 'stats'));
    }

    public function historique(Request $request)
    {
        $query = Paiement::with(['patient', 'facture']);

        if ($request->date) {
            $query->whereDate('date_paiement', $request->date);
        }

        if ($request->mode) {
            $query->where('mode_paiement', $request->mode);
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(20);

        return view('caisse.historique', compact('paiements'));
    }

    public function journal()
    {
        $transactions = Transaction::orderBy('date', 'desc')->get();

        $totalEntrees = $transactions->where('type', 'entree')->sum('montant');
        $totalSorties = $transactions->where('type', 'sortie')->sum('montant');
        $solde = $totalEntrees - $totalSorties;

        return view('caisse.journal', compact('transactions', 'totalEntrees', 'totalSorties', 'solde'));
    }

    /**
     * Enregistrer une nouvelle dépense
     */
    public function storeDepense(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'montant' => 'required|numeric|min:1',
            'categorie' => 'nullable|string|max:100',
        ]);

        Transaction::create([
            'date' => now(),
            'type' => 'sortie',
            'montant' => $validated['montant'],
            'description' => $validated['description'],
            'categorie' => $validated['categorie'] ?? 'autre',
        ]);

        return redirect()->route('caisse.journal')
            ->with('success', 'Dépense enregistrée avec succès');
    }

    /**
     * Obtenir le nombre de factures en attente (pour badge)
     */
    public static function getFacturesEnAttenteCount()
    {
        return Facture::where('statut', 'en_attente')->count();
    }
}
