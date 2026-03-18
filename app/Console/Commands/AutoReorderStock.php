<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicament;
use App\Models\FicheApprovisionnement;

class AutoReorderStock extends Command
{
    protected $signature = 'stock:auto-reorder';
    protected $description = 'Crée automatiquement des demandes d\'approvisionnement pour les médicaments en stock critique';

    public function handle()
    {
        $medicaments = Medicament::whereColumn('stock', '<=', 'stock_min')->get();

        if ($medicaments->isEmpty()) {
            $this->info('Tous les stocks sont au-dessus du seuil minimum');
            return 0;
        }

        $numero = 'AUTO-' . date('Ymd') . '-' . str_pad(FicheApprovisionnement::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);

        $fiche = FicheApprovisionnement::create([
            'numero' => $numero,
            'date' => today(),
            'fournisseur' => 'À déterminer',
            'total_articles' => $medicaments->count(),
            'total_quantite' => 0,
            'montant_total' => 0,
            'statut' => 'en_attente',
            'observations' => 'Commande auto-générée - Stock critique détecté',
            'cree_par' => 'Système',
        ]);

        $totalQte = 0;
        $totalMontant = 0;

        foreach ($medicaments as $med) {
            $qteRecommandee = max(($med->stock_min * 2) - $med->stock, $med->stock_min);
            $prix = $med->prix_unitaire ?? 0;

            \App\Models\ApprovisionnementLigne::create([
                'fiche_approvisionnement_id' => $fiche->id,
                'medicament_id' => $med->id,
                'quantite' => $qteRecommandee,
                'prix_unitaire' => $prix,
            ]);

            $totalQte += $qteRecommandee;
            $totalMontant += $qteRecommandee * $prix;
        }

        $fiche->update([
            'total_quantite' => $totalQte,
            'montant_total' => $totalMontant,
        ]);

        // Notify pharmacist
        \App\Models\User::where('role', 'pharmacie')->each(function($u) use ($fiche, $medicaments) {
            $u->notify(new \App\Notifications\StockAutoReorder($fiche, $medicaments->count()));
        });

        $this->info("Commande $numero créée: {$medicaments->count()} médicaments, quantité totale: $totalQte");
        return 0;
    }
}
