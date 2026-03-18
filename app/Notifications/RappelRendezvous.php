<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Consultation;

class RappelRendezvous extends Notification
{
    use Queueable;

    public function __construct(public Consultation $consultation) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $patient = $this->consultation->patient;
        $medecin = $this->consultation->medecin;
        $date = $this->consultation->date->format('d/m/Y');
        $heure = $this->consultation->heure;

        return (new MailMessage)
            ->subject('Rappel de rendez-vous - MediCare Pro')
            ->greeting('Bonjour ' . $patient->prenom . ' ' . $patient->nom . ',')
            ->line('Nous vous rappelons votre rendez-vous medical :')
            ->line('Date : ' . $date)
            ->line('Heure : ' . $heure)
            ->line('Medecin : Dr. ' . $medecin->prenom . ' ' . $medecin->nom . ' (' . $medecin->specialite . ')')
            ->line('Motif : ' . $this->consultation->motif)
            ->line('')
            ->line('Merci de vous presenter 15 minutes avant l\'heure prevue.')
            ->line('En cas d\'empechement, veuillez nous contacter au plus tot.')
            ->salutation('Cordialement, L\'equipe MediCare Pro');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'rappel_rdv',
            'icon' => 'check-circle',
            'message' => 'Rappel envoye a ' . $this->consultation->patient->prenom . ' ' . $this->consultation->patient->nom . ' pour le ' . $this->consultation->date->format('d/m/Y') . ' a ' . $this->consultation->heure,
            'url' => route('reception.consultations.index'),
        ];
    }
}
