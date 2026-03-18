<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificatMedical extends Model
{
    protected $table = 'certificats_medicaux';

    protected $fillable = [
        'numero',
        'patient_id',
        'medecin_id',
        'consultation_id',
        'type',
        'date_emission',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'observations',
        'conclusion',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
