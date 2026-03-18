<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'patient_id',
        'consultation_id',
        'fiche_traitement_id',
        'date',
        'montant',
        'statut',
        'envoye_par',
        'mode_paiement',
        'date_paiement',
    ];

    protected $casts = [
        'date' => 'date',
        'date_paiement' => 'datetime',
    ];

    protected $appends = ['montant_total'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function ficheTraitement()
    {
        return $this->belongsTo(FicheTraitement::class);
    }

    public function lignes()
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Accessor pour montant_total - calcule depuis les lignes ou utilise montant
    public function getMontantTotalAttribute()
    {
        // Vérifier si lignes est déjà chargé pour éviter les requêtes N+1
        if ($this->relationLoaded('lignes') && $this->lignes->count() > 0) {
            return $this->lignes->sum(function($ligne) {
                return $ligne->total ?? ($ligne->quantite * $ligne->prix_unitaire);
            });
        }
        return $this->montant ?? 0;
    }
}
