<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExamenLabo extends Model
{
    protected $table = 'examens_labo';
    protected $fillable = ['nom', 'categorie', 'unite', 'valeur_normale', 'prix', 'actif'];

    public function resultats() { return $this->hasMany(ResultatLabo::class); }
}
