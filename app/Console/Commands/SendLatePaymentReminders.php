<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facture;

class SendLatePaymentReminders extends Command
{
    protected $signature = 'factures:rappels-impayees';
    protected $description = 'Envoie des rappels pour les factures impayées > 30 jours';

    public function handle()
    {
        $factures30 = Facture::with('patient')
            ->whereIn('statut', ['en_attente', 'envoyee'])
            ->where('date', '<', now()->subDays(30))
            ->get();

        $envoyes = 0;
        foreach ($factures30 as $facture) {
            if ($facture->patient->email) {
                try {
                    $facture->patient->notify(new \App\Notifications\RappelFactureImpayee($facture));
                    $envoyes++;
                } catch (\Exception $e) {}
            }

            // Notify caisse staff
            \App\Models\User::where('role', 'caisse')->each(fn($u) => $u->notify(new \App\Notifications\RappelFactureImpayee($facture)));
        }

        $this->info("$envoyes rappels envoyés sur {$factures30->count()} factures impayées > 30 jours");
        return 0;
    }
}
