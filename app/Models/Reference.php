<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'numero', 'patient_id', 'medecin_referent_id', 'medecin_cible_id',
        'consultation_id', 'date_reference', 'motif', 'contexte_clinique',
        'examens_joints', 'urgence', 'statut', 'reponse_specialiste',
        'date_consultation_specialiste',
    ];
    protected $casts = ['date_reference' => 'date', 'date_consultation_specialiste' => 'date'];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function medecinReferent() { return $this->belongsTo(Medecin::class, 'medecin_referent_id'); }
    public function medecinCible() { return $this->belongsTo(Medecin::class, 'medecin_cible_id'); }
    public function consultation() { return $this->belongsTo(Consultation::class); }
}
