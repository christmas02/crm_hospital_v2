<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RapportQuotidien extends Notification
{
    use Queueable;
    public function __construct(public array $data) {}
    public function via($notifiable) { return ['database']; }
    public function toArray($notifiable)
    {
        return [
            'type' => 'rapport_quotidien',
            'icon' => 'check-circle',
            'message' => '📊 Rapport du ' . $this->data['date'] . ': ' . $this->data['consultations_total'] . ' consultations, ' . number_format($this->data['recettes'], 0, ',', ' ') . ' F recettes',
            'url' => route('admin.analytics'),
        ];
    }
}
