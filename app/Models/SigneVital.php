<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SigneVital extends Model
{
    protected $table = 'signes_vitaux';

    protected $fillable = [
        'patient_id', 'consultation_id', 'pris_par',
        'temperature', 'tension_systolique', 'tension_diastolique',
        'pouls', 'frequence_respiratoire', 'saturation_o2',
        'poids', 'taille', 'imc', 'glycemie', 'notes',
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function consultation() { return $this->belongsTo(Consultation::class); }
    public function prisPar() { return $this->belongsTo(User::class, 'pris_par'); }
}
