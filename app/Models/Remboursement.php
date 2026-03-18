<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remboursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'paiement_id',
        'facture_id',
        'patient_id',
        'montant',
        'motif',
        'mode_remboursement',
        'notes',
        'statut',
        'effectue_par',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function effectueur()
    {
        return $this->belongsTo(User::class, 'effectue_par');
    }
}
