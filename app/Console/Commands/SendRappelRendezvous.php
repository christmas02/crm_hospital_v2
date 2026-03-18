<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Consultation;
use App\Notifications\RappelRendezvous;

class SendRappelRendezvous extends Command
{
    protected $signature = 'rappels:envoyer {--jours=1 : Nombre de jours avant le RDV}';
    protected $description = 'Envoie les rappels de rendez-vous aux patients';

    public function handle()
    {
        $jours = (int) $this->option('jours');
        $dateRappel = now()->addDays($jours)->format('Y-m-d');

        $consultations = Consultation::with(['patient', 'medecin'])
            ->whereDate('date', $dateRappel)
            ->where('statut', 'en_attente')
            ->get();

        $envoyes = 0;
        $erreurs = 0;

        foreach ($consultations as $consultation) {
            if ($consultation->patient->email) {
                try {
                    $consultation->patient->notify(new RappelRendezvous($consultation));
                    $envoyes++;
                    $this->info("Rappel envoye a {$consultation->patient->prenom} {$consultation->patient->nom}");
                } catch (\Exception $e) {
                    $erreurs++;
                    $this->error("Erreur pour {$consultation->patient->prenom} {$consultation->patient->nom}: {$e->getMessage()}");
                }
            }
        }

        // Also notify reception staff (in-app only)
        $receptionUsers = \App\Models\User::where('role', 'reception')->get();
        foreach ($consultations as $consultation) {
            foreach ($receptionUsers as $user) {
                try {
                    $user->notify(new RappelRendezvous($consultation));
                } catch (\Exception $e) {
                    // Silently continue
                }
            }
        }

        $this->info("Termine: {$envoyes} rappels envoyes, {$erreurs} erreurs, {$consultations->count()} RDV trouves pour le {$dateRappel}");

        return 0;
    }
}
