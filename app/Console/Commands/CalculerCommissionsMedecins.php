<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medecin;
use App\Models\Consultation;

class CalculerCommissionsMedecins extends Command
{
    protected $signature = 'medecins:commissions {--mois= : Mois (1-12)} {--annee= : Année}';
    protected $description = 'Calcule les commissions des médecins pour un mois donné';

    public function handle()
    {
        $mois = $this->option('mois') ?: now()->month;
        $annee = $this->option('annee') ?: now()->year;
        $debut = \Carbon\Carbon::createFromDate($annee, $mois, 1)->startOfMonth();
        $fin = $debut->copy()->endOfMonth();

        $medecins = Medecin::all();

        $this->info("Commissions des médecins - " . $debut->locale('fr')->isoFormat('MMMM YYYY'));
        $this->info(str_repeat('-', 80));

        foreach ($medecins as $medecin) {
            $nbConsultations = Consultation::where('medecin_id', $medecin->id)
                ->where('statut', 'termine')
                ->whereBetween('date', [$debut, $fin])
                ->count();

            $revenuGenere = $nbConsultations * ($medecin->tarif_consultation ?? 0);
            $commission = (int)round($revenuGenere * ($medecin->taux_commission ?? 0) / 100);
            $totalSalaire = ($medecin->salaire_base ?? 0) + $commission;

            $this->info(sprintf(
                "Dr. %-20s | %3d consultations | Revenu: %10s F | Commission (%d%%): %10s F | Salaire total: %10s F",
                $medecin->prenom . ' ' . $medecin->nom,
                $nbConsultations,
                number_format($revenuGenere, 0, ',', ' '),
                $medecin->taux_commission ?? 0,
                number_format($commission, 0, ',', ' '),
                number_format($totalSalaire, 0, ',', ' ')
            ));
        }

        return 0;
    }
}
