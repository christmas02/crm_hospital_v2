<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'patient_id',
        'medecin_id',
        'date',
        'numero_retrait',
        'statut_dispensation',
        'date_preparation',
        'date_remise',
        'remis_a',
        'recommandations',
    ];

    protected $casts = [
        'date' => 'date',
        'date_preparation' => 'date',
        'date_remise' => 'date',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function medicaments()
    {
        return $this->hasMany(OrdonnanceMedicament::class);
    }
}
