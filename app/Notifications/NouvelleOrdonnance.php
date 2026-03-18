<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleOrdonnance extends Notification
{
    use Queueable;
    public function __construct(public $ordonnance) {}
    public function via($notifiable) { return ['database']; }
    public function toArray($notifiable)
    {
        return [
            'type' => 'nouvelle_ordonnance',
            'icon' => 'check-circle',
            'message' => 'Nouvelle ordonnance à préparer pour ' . $this->ordonnance->patient->prenom . ' ' . $this->ordonnance->patient->nom,
            'url' => route('pharmacie.demandes'),
        ];
    }
}
