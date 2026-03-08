<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $table = 'mouvements_stock';

    protected $fillable = [
        'medicament_id',
        'type',
        'quantite',
        'date',
        'motif',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }
}
