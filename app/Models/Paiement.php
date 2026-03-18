<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'facture_id',
        'date_paiement',
        'montant',
        'type',
        'description',
        'mode_paiement',
        'statut',
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
}
