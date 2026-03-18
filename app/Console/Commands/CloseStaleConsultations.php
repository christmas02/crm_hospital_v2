<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Consultation;

class CloseStaleConsultations extends Command
{
    protected $signature = 'consultations:close-stale {--jours=1 : Nombre de jours d\'inactivité}';
    protected $description = 'Ferme automatiquement les consultations en attente depuis plus de X jours';

    public function handle()
    {
        $jours = (int)$this->option('jours');
        $dateLimite = now()->subDays($jours);

        $stales = Consultation::where('statut', 'en_attente')
            ->where('date', '<', $dateLimite->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($stales as $consultation) {
            $consultation->update(['statut' => 'annulee']);

            // Delete associated file d'attente
            \App\Models\FileAttente::where('consultation_id', $consultation->id)->delete();

            $count++;
        }

        $this->info("$count consultations en attente fermées (> $jours jour(s))");
        return 0;
    }
}
