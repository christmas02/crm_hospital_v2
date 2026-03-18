<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medecin extends Model
{
    use HasFactory, SoftDeletes;
    use \App\Traits\TracksChanges;

    protected $fillable = [
        'nom',
        'prenom',
        'specialite',
        'telephone',
        'email',
        'bureau',
        'statut',
        'tarif_consultation',
        'taux_commission',
        'salaire_base',
        'photo',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function referencesEmises()
    {
        return $this->hasMany(Reference::class, 'medecin_referent_id');
    }

    public function referencesRecues()
    {
        return $this->hasMany(Reference::class, 'medecin_cible_id');
    }

    public function getNomCompletAttribute()
    {
        return 'Dr. ' . $this->prenom . ' ' . $this->nom;
    }
}
