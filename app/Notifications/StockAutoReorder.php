<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockAutoReorder extends Notification
{
    use Queueable;
    public function __construct(public $fiche, public int $nbMedicaments) {}
    public function via($notifiable) { return ['database']; }
    public function toArray($notifiable)
    {
        return [
            'type' => 'stock_reorder',
            'icon' => 'alert',
            'message' => 'Commande auto ' . $this->fiche->numero . ' créée: ' . $this->nbMedicaments . ' médicaments en stock critique',
            'url' => route('pharmacie.approvisionnements'),
        ];
    }
}
