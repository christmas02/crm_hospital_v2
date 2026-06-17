<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    use \App\Traits\TracksChanges;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'sexe',
        'telephone',
        'email',
        'adresse',
        'groupe_sanguin',
        'allergies',
        'date_inscription',
        'statut',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'allergies' => 'array',
    ];

    public function dossierMedical()
    {
        return $this->hasOne(DossierMedical::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function hospitalisations()
    {
        return $this->hasMany(Hospitalisation::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function rendezvous()
    {
        return $this->hasMany(Rendezvous::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function documents()
    {
        return $this->hasMany(PatientDocument::class);
    }

    public function signesVitaux()
    {
        return $this->hasMany(SigneVital::class);
    }

    public function certificats()
    {
        return $this->hasMany(CertificatMedical::class);
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    public function demandesLabo() { return $this->hasMany(DemandeLabo::class); }

    public function references() { return $this->hasMany(Reference::class); }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
