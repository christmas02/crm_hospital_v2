<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'categorie',
        'forme',
        'dosage',
        'stock',
        'stock_min',
        'prix_unitaire',
        'fournisseur',
    ];

    protected $appends = ['prix'];

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function lignesApprovisionnement()
    {
        return $this->hasMany(ApprovisionnementLigne::class);
    }

    public function ordonnances()
    {
        return $this->belongsToMany(Ordonnance::class, 'ordonnance_medicaments')
            ->withPivot('posologie', 'duree', 'quantite')
            ->withTimestamps();
    }

    public function isStockBas()
    {
        return $this->stock <= $this->stock_min;
    }

    // Accessor pour prix - alias de prix_unitaire
    public function getPrixAttribute()
    {
        return $this->prix_unitaire;
    }
}
