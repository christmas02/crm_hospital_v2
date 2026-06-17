<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActeMedical extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actes_medicaux';

    protected $fillable = [
        'code',
        'nom',
        'categorie',
        'prix',
        'facturable',
    ];

    protected $casts = [
        'facturable' => 'boolean',
    ];

    public function fichesTraitement()
    {
        return $this->belongsToMany(FicheTraitement::class, 'fiche_traitement_actes')
            ->withPivot('nom', 'prix', 'quantite', 'facturable')
            ->withTimestamps();
    }
}
