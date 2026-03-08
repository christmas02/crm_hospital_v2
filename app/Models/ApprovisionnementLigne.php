<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovisionnementLigne extends Model
{
    use HasFactory;

    protected $table = 'approvisionnement_lignes';

    protected $fillable = [
        'fiche_approvisionnement_id',
        'medicament_id',
        'nom',
        'quantite',
        'prix_unitaire',
    ];

    public function ficheApprovisionnement()
    {
        return $this->belongsTo(FicheApprovisionnement::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }
}
