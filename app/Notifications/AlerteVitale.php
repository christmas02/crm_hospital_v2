<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AlerteVitale extends Notification
{
    use Queueable;

    public function __construct(public $patient, public array $alerts) {}

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'type' => 'alerte_vitale',
            'icon' => 'alert',
            'message' => '⚠️ Alerte vitale pour ' . $this->patient->prenom . ' ' . $this->patient->nom . ': ' . implode(', ', $this->alerts),
            'url' => '#',
        ];
    }
}
