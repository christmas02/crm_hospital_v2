<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RappelFactureImpayee extends Notification
{
    use Queueable;

    public function __construct(public $facture) {}

    public function via($notifiable) { return ['database', 'mail']; }

    public function toMail($notifiable)
    {
        $restant = ($this->facture->montant_net ?: $this->facture->montant) - $this->facture->montant_paye;
        return (new MailMessage)
            ->subject('Rappel - Facture ' . $this->facture->numero . ' impayée')
            ->greeting('Bonjour,')
            ->line('Nous vous rappelons que la facture ' . $this->facture->numero . ' du ' . $this->facture->date->format('d/m/Y') . ' reste impayée.')
            ->line('Montant restant: ' . number_format($restant, 0, ',', ' ') . ' F CFA')
            ->line('Nous vous prions de bien vouloir régulariser votre situation.')
            ->salutation('Cordialement, L\'équipe MediCare Pro');
    }

    public function toArray($notifiable)
    {
        $restant = ($this->facture->montant_net ?: $this->facture->montant) - $this->facture->montant_paye;
        return [
            'type' => 'rappel_facture',
            'icon' => 'alert',
            'message' => 'Facture impayée ' . $this->facture->numero . ' - ' . number_format($restant, 0, ',', ' ') . ' F (' . $this->facture->patient->prenom . ' ' . $this->facture->patient->nom . ')',
            'url' => route('caisse.factures.index'),
        ];
    }
}
