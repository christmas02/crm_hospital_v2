<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use SoftDeletes;

    protected $table = 'personnel';

    protected $fillable = [
        'matricule', 'nom', 'prenom', 'date_naissance', 'sexe',
        'telephone', 'email', 'adresse', 'photo',
        'categorie', 'poste', 'service',
        'date_embauche', 'date_fin_contrat', 'type_contrat',
        'salaire', 'statut', 'contact_urgence', 'telephone_urgence',
        'qualifications', 'notes', 'user_id',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_embauche' => 'date',
        'date_fin_contrat' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getAncienneteAttribute()
    {
        return $this->date_embauche->diffInYears(now());
    }
}
