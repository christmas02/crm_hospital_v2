<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospitalisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'chambre_id',
        'medecin_id',
        'date_admission',
        'date_sortie',
        'motif',
        'statut',
    ];

    protected $casts = [
        'date_admission' => 'date',
        'date_sortie' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }
}
