<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Medicament;

class StockBas extends Notification
{
    use Queueable;

    public function __construct(public Medicament $medicament) {}

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'type' => 'stock_bas',
            'icon' => 'alert',
            'message' => 'Stock bas : ' . $this->medicament->nom . ' (' . $this->medicament->stock . ' restants)',
            'url' => route('pharmacie.alertes'),
        ];
    }
}
