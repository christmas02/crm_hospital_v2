<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facture extends Model
{
    use HasFactory, SoftDeletes;

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
        'montant_remise',
        'montant_tva',
        'montant_net',
        'montant_paye',
        'montant_restant',
        'notes',
        'reference_paiement',
        'encaisse_par',
        'type_prise_en_charge',
        'organisme_prise_en_charge',
        'numero_assurance',
        'taux_couverture',
        'montant_couvert',
        'montant_patient',
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

    public function encaisseur()
    {
        return $this->belongsTo(\App\Models\User::class, 'encaisse_par');
    }

    public function avoirs()
    {
        return $this->hasMany(\App\Models\Avoir::class);
    }

    public function remboursements()
    {
        return $this->hasMany(\App\Models\Remboursement::class);
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

    public function estPartiellementPayee(): bool
    {
        return $this->montant_paye > 0 && $this->montant_paye < $this->montant_net;
    }

    public function calculerMontants()
    {
        $sousTotal = $this->lignes->sum(fn($l) => $l->quantite * $l->prix_unitaire);
        $this->montant = $sousTotal;
        $this->montant_net = $sousTotal - $this->montant_remise + $this->montant_tva;
        $this->montant_restant = $this->montant_net - $this->montant_paye;
        $this->save();
    }
}
