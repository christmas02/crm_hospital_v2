<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'facture_id',
        'date_paiement',
        'montant',
        'type',
        'description',
        'mode_paiement',
        'statut',
        'numero_recu',
        'reference',
        'notes',
        'encaisse_par',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function encaisseur()
    {
        return $this->belongsTo(\App\Models\User::class, 'encaisse_par');
    }
}
