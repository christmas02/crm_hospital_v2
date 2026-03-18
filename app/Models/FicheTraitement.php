<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FicheTraitement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fiches_traitement';

    protected $fillable = [
        'consultation_id',
        'patient_id',
        'medecin_id',
        'date',
        'diagnostic',
        'observations',
        'total_facturable',
    ];

    protected $casts = [
        'date' => 'date',
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

    // Méthode principale pour les actes médicaux
    public function actesMedicaux()
    {
        return $this->belongsToMany(ActeMedical::class, 'fiche_traitement_actes')
            ->withPivot('nom', 'prix', 'quantite', 'facturable')
            ->withTimestamps();
    }

    // Alias pour compatibilité
    public function actes()
    {
        return $this->actesMedicaux();
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}
