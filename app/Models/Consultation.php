<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'date',
        'heure',
        'motif',
        'diagnostic',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'heure' => 'datetime:H:i',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function ficheTraitement()
    {
        return $this->hasOne(FicheTraitement::class);
    }

    public function ordonnance()
    {
        return $this->hasOne(Ordonnance::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function fileAttente()
    {
        return $this->hasOne(FileAttente::class);
    }
}
