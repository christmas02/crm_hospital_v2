<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleReference extends Notification
{
    use Queueable;

    public function __construct(public $patient, public $medecinReferent) {}

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'type' => 'nouvelle_reference',
            'icon' => 'user-plus',
            'message' => 'Nouvelle reference recue : ' . $this->patient->prenom . ' ' . $this->patient->nom . ' (de Dr. ' . $this->medecinReferent->nom . ')',
            'url' => route('medecin.index'),
        ];
    }
}
