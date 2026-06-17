<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FicheApprovisionnement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fiches_approvisionnement';

    protected $fillable = [
        'numero',
        'date',
        'fournisseur',
        'total_articles',
        'total_quantite',
        'montant_total',
        'statut',
        'date_reception',
        'observations',
        'cree_par',
    ];

    protected $casts = [
        'date' => 'date',
        'date_reception' => 'date',
    ];

    public function lignes()
    {
        return $this->hasMany(ApprovisionnementLigne::class);
    }
}
