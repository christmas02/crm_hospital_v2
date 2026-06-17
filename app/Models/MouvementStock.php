<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MouvementStock extends Model
{
    use HasFactory, SoftDeletes;

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
