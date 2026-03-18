<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Paiement;
use App\Models\Transaction;

class GenerateDailyReport extends Command
{
    protected $signature = 'rapports:quotidien';
    protected $description = 'Génère et envoie le rapport quotidien aux administrateurs';

    public function handle()
    {
        $today = today();

        $data = [
            'date' => $today->locale('fr')->isoFormat('dddd D MMMM YYYY'),
            'nouveaux_patients' => Patient::whereDate('date_inscription', $today)->count(),
            'consultations_total' => Consultation::whereDate('date', $today)->count(),
            'consultations_terminees' => Consultation::whereDate('date', $today)->where('statut', 'termine')->count(),
            'consultations_attente' => Consultation::whereDate('date', $today)->where('statut', 'en_attente')->count(),
            'recettes' => Paiement::whereDate('date_paiement', $today)->where('statut', 'paye')->sum('montant'),
            'depenses' => Transaction::whereDate('date', $today)->where('type', 'sortie')->sum('montant'),
        ];
        $data['solde'] = $data['recettes'] - $data['depenses'];

        // Notify admin users
        \App\Models\User::where('role', 'admin')->each(function($u) use ($data) {
            $u->notify(new \App\Notifications\RapportQuotidien($data));
        });

        $this->info("Rapport quotidien généré: {$data['consultations_total']} consultations, " . number_format($data['recettes'], 0, ',', ' ') . " F recettes");
        return 0;
    }
}
