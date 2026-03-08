<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureLigne extends Model
{
    use HasFactory;

    protected $table = 'facture_lignes';

    protected $fillable = [
        'facture_id',
        'description',
        'quantite',
        'prix_unitaire',
        'total',
    ];

    protected $appends = ['montant'];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    // Accessor pour montant - alias de total
    public function getMontantAttribute()
    {
        return $this->total ?? (($this->quantite ?? 1) * ($this->prix_unitaire ?? 0));
    }
}
