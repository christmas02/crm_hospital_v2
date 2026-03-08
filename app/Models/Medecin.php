<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'specialite',
        'telephone',
        'email',
        'bureau',
        'statut',
        'tarif_consultation',
    ];

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function hospitalisations()
    {
        return $this->hasMany(Hospitalisation::class);
    }

    public function fichesTraitement()
    {
        return $this->hasMany(FicheTraitement::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function planning()
    {
        return $this->hasMany(Planning::class);
    }

    public function fileAttente()
    {
        return $this->hasMany(FileAttente::class);
    }

    public function rendezvous()
    {
        return $this->hasMany(Rendezvous::class);
    }

    public function getNomCompletAttribute()
    {
        return 'Dr. ' . $this->prenom . ' ' . $this->nom;
    }
}
