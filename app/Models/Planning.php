<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planning extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'planning';

    protected $fillable = [
        'medecin_id',
        'jour',
        'debut',
        'fin',
    ];

    protected $casts = [
        'debut' => 'datetime:H:i',
        'fin' => 'datetime:H:i',
    ];

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }
}
