<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avoir extends Model
{
    protected $fillable = ['numero', 'facture_id', 'patient_id', 'montant', 'motif', 'notes', 'statut', 'created_by'];

    public function facture() { return $this->belongsTo(Facture::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function createur() { return $this->belongsTo(User::class, 'created_by'); }
}
