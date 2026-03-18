<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'etage',
        'type',
        'capacite',
        'tarif_jour',
        'statut',
        'patient_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function hospitalisations()
    {
        return $this->hasMany(Hospitalisation::class);
    }
}
