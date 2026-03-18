<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationNote extends Model
{
    protected $fillable = ['consultation_id', 'user_id', 'contenu'];

    public function consultation() { return $this->belongsTo(Consultation::class); }
    public function user() { return $this->belongsTo(User::class); }
}
