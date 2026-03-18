<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleFacture extends Notification
{
    use Queueable;
    public function __construct(public $facture) {}
    public function via($notifiable) { return ['database']; }
    public function toArray($notifiable)
    {
        return [
            'type' => 'nouvelle_facture',
            'icon' => 'check-circle',
            'message' => 'Nouvelle facture ' . $this->facture->numero . ' - ' . number_format($this->facture->montant, 0, ',', ' ') . ' F à encaisser',
            'url' => route('caisse.factures.index'),
        ];
    }
}
