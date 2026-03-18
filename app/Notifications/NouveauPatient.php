<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Patient;

class NouveauPatient extends Notification
{
    use Queueable;

    public function __construct(public Patient $patient) {}

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'type' => 'nouveau_patient',
            'icon' => 'user-plus',
            'message' => 'Nouveau patient enregistré : ' . $this->patient->prenom . ' ' . $this->patient->nom,
            'url' => route('reception.patients.show', $this->patient),
        ];
    }
}
