<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DossierMedical extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dossiers_medicaux';

    protected $fillable = [
        'patient_id',
        'antecedents',
        'maladies_chroniques',
        'chirurgies',
        'notes',
    ];

    protected $casts = [
        'antecedents' => 'array',
        'maladies_chroniques' => 'array',
        'chirurgies' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
