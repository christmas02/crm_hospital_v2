<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaisseSession extends Model
{
    protected $fillable = ['user_id', 'ouverture', 'fermeture', 'solde_ouverture', 'solde_fermeture', 'total_encaissements', 'total_depenses', 'notes_ouverture', 'notes_fermeture', 'statut'];

    protected $casts = [
        'ouverture' => 'datetime',
        'fermeture' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
