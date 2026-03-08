<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'sexe',
        'telephone',
        'email',
        'adresse',
        'groupe_sanguin',
        'allergies',
        'date_inscription',
        'statut',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'allergies' => 'array',
    ];

    public function dossierMedical()
    {
        return $this->hasOne(DossierMedical::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function hospitalisations()
    {
        return $this->hasMany(Hospitalisation::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function rendezvous()
    {
        return $this->hasMany(Rendezvous::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
