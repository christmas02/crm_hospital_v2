<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdonnanceMedicament extends Model
{
    use HasFactory;

    protected $table = 'ordonnance_medicaments';

    protected $fillable = [
        'ordonnance_id',
        'nom',
        'posologie',
        'duree',
        'quantite',
    ];

    public function ordonnance()
    {
        return $this->belongsTo(Ordonnance::class);
    }
}
