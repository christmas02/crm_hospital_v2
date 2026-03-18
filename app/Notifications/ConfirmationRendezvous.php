<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Consultation;

class ConfirmationRendezvous extends Notification
{
    use Queueable;

    public function __construct(public Consultation $consultation) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $patient = $this->consultation->patient;
        $medecin = $this->consultation->medecin;

        return (new MailMessage)
            ->subject('Confirmation de rendez-vous - MediCare Pro')
            ->greeting('Bonjour ' . $patient->prenom . ' ' . $patient->nom . ',')
            ->line('Votre rendez-vous a bien ete enregistre :')
            ->line('Date : ' . $this->consultation->date->format('d/m/Y'))
            ->line('Heure : ' . $this->consultation->heure)
            ->line('Medecin : Dr. ' . $medecin->prenom . ' ' . $medecin->nom)
            ->line('Motif : ' . $this->consultation->motif)
            ->line('')
            ->line('Merci de vous presenter 15 minutes avant l\'heure prevue avec votre carnet de sante.')
            ->salutation('Cordialement, L\'equipe MediCare Pro');
    }
}
