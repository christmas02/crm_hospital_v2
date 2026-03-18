<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    protected $fillable = [
        'patient_id', 'vaccin', 'maladie', 'date_administration',
        'dose', 'lot', 'site_injection', 'prochain_rappel',
        'administre_par', 'notes',
    ];

    protected $casts = [
        'date_administration' => 'date',
        'prochain_rappel' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function administrePar()
    {
        return $this->belongsTo(User::class, 'administre_par');
    }
}
