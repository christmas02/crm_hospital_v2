<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chambre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'etage',
        'type',
        'capacite',
        'tarif_jour',
        'statut',
        'patient_id',
        'equipements',
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
