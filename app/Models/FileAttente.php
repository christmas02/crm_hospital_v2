<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileAttente extends Model
{
    use HasFactory;

    protected $table = 'file_attente';

    protected $fillable = [
        'consultation_id',
        'patient_id',
        'medecin_id',
        'heure_arrivee',
        'position',
        'statut',
    ];

    protected $casts = [
        'heure_arrivee' => 'datetime:H:i',
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
}
