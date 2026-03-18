<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResultatCritique extends Notification
{
    use Queueable;

    public function __construct(public $demande, public string $alertMsg) {}

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'type' => 'resultat_critique',
            'icon' => 'alert',
            'message' => '🚨 Résultat CRITIQUE - ' . $this->demande->patient->prenom . ' ' . $this->demande->patient->nom . ': ' . $this->alertMsg,
            'url' => route('labo.index'),
        ];
    }
}
