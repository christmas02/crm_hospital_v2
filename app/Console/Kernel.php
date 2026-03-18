<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Rappels RDV : envoyer chaque jour à 18h pour les RDV du lendemain
        $schedule->command('rappels:envoyer --jours=1')
            ->dailyAt('18:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/rappels.log'));

        // Backup BDD : chaque jour à 2h du matin
        $schedule->command('db:backup')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/backup.log'));

        // Fermer les consultations en attente > 1 jour
        $schedule->command('consultations:close-stale --jours=1')
            ->dailyAt('23:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/stale-consultations.log'));

        // Rappels factures impayées (tous les lundis)
        $schedule->command('factures:rappels-impayees')
            ->weeklyOn(1, '09:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/rappels-factures.log'));

        // Auto-réapprovisionnement stock critique (tous les jours à 07:00)
        $schedule->command('stock:auto-reorder')
            ->dailyAt('07:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/auto-reorder.log'));

        // Rapport quotidien (tous les soirs à 20:00)
        $schedule->command('rapports:quotidien')
            ->dailyAt('20:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/rapport-quotidien.log'));

        // Commissions médecins (1er de chaque mois)
        $schedule->command('medecins:commissions')
            ->monthlyOn(1, '06:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/commissions.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
