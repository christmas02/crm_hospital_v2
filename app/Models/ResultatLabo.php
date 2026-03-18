<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ResultatLabo extends Model
{
    protected $table = 'resultats_labo';
    protected $fillable = ['demande_labo_id', 'examen_labo_id', 'valeur', 'unite', 'valeur_reference', 'interpretation', 'commentaire'];

    public function demande() { return $this->belongsTo(DemandeLabo::class, 'demande_labo_id'); }
    public function examen() { return $this->belongsTo(ExamenLabo::class, 'examen_labo_id'); }
}
