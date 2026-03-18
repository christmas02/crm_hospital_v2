<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DemandeLabo extends Model
{
    protected $table = 'demandes_labo';
    protected $fillable = ['numero', 'patient_id', 'medecin_id', 'consultation_id', 'date_demande', 'statut', 'urgence', 'notes_cliniques', 'date_resultat', 'realise_par'];
    protected $casts = ['date_demande' => 'date', 'date_resultat' => 'date'];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function medecin() { return $this->belongsTo(Medecin::class); }
    public function consultation() { return $this->belongsTo(Consultation::class); }
    public function resultats() { return $this->hasMany(ResultatLabo::class); }
    public function realisePar() { return $this->belongsTo(User::class, 'realise_par'); }
}
