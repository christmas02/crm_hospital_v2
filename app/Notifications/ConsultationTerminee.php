<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Consultation;

class ConsultationTerminee extends Notification
{
    use Queueable;

    public function __construct(public Consultation $consultation) {}

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'type' => 'consultation_terminee',
            'icon' => 'check-circle',
            'message' => 'Consultation terminée pour ' . $this->consultation->patient->prenom . ' ' . $this->consultation->patient->nom,
            'url' => route('reception.consultations.index'),
        ];
    }
}
