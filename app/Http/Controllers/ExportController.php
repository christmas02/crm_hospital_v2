<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Medecin;
use App\Models\Medicament;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    private function streamCsv(string $filename, array $headers, $query, callable $rowMapper): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $query, $rowMapper) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $headers, ';');

            $query->chunk(500, function ($items) use ($handle, $rowMapper) {
                foreach ($items as $item) {
                    fputcsv($handle, $rowMapper($item), ';');
                }
            });
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function patients()
    {
        return $this->streamCsv(
            'patients_' . date('Y-m-d') . '.csv',
            ['ID', 'Nom', 'Prénom', 'Date naissance', 'Sexe', 'Téléphone', 'Email', 'Adresse', 'Groupe sanguin', 'Statut', 'Date inscription'],
            Patient::orderBy('nom'),
            fn($p) => [$p->id, $p->nom, $p->prenom, $p->date_naissance?->format('d/m/Y'), $p->sexe, $p->telephone, $p->email, $p->adresse, $p->groupe_sanguin, $p->statut, $p->date_inscription?->format('d/m/Y')]
        );
    }

    public function consultations()
    {
        return $this->streamCsv(
            'consultations_' . date('Y-m-d') . '.csv',
            ['ID', 'Date', 'Heure', 'Patient', 'Médecin', 'Motif', 'Statut'],
            Consultation::with(['patient', 'medecin'])->orderBy('date', 'desc'),
            fn($c) => [$c->id, $c->date?->format('d/m/Y'), $c->heure, $c->patient?->prenom . ' ' . $c->patient?->nom, 'Dr. ' . $c->medecin?->nom, $c->motif, $c->statut]
        );
    }

    public function medecins()
    {
        return $this->streamCsv(
            'medecins_' . date('Y-m-d') . '.csv',
            ['ID', 'Nom', 'Prénom', 'Spécialité', 'Téléphone', 'Email', 'Bureau', 'Tarif', 'Statut'],
            Medecin::orderBy('nom'),
            fn($m) => [$m->id, $m->nom, $m->prenom, $m->specialite, $m->telephone, $m->email, $m->bureau, $m->tarif_consultation, $m->statut]
        );
    }

    public function medicaments()
    {
        return $this->streamCsv(
            'medicaments_' . date('Y-m-d') . '.csv',
            ['ID', 'Nom', 'Forme', 'Dosage', 'Catégorie', 'Stock', 'Stock min', 'Prix unitaire'],
            Medicament::orderBy('nom'),
            fn($m) => [$m->id, $m->nom, $m->forme, $m->dosage, $m->categorie, $m->stock, $m->stock_min, $m->prix_unitaire]
        );
    }

    public function factures()
    {
        return $this->streamCsv(
            'factures_' . date('Y-m-d') . '.csv',
            ['ID', 'Patient', 'Date', 'Montant', 'Statut'],
            Facture::with('patient')->orderBy('created_at', 'desc'),
            fn($f) => [$f->id, $f->patient?->prenom . ' ' . $f->patient?->nom, $f->created_at?->format('d/m/Y'), $f->montant, $f->statut]
        );
    }

    public function transactions()
    {
        return $this->streamCsv(
            'transactions_' . date('Y-m-d') . '.csv',
            ['ID', 'Date', 'Type', 'Montant', 'Description', 'Catégorie'],
            Transaction::orderBy('date', 'desc'),
            fn($t) => [$t->id, $t->date, $t->type, $t->montant, $t->description, $t->categorie]
        );
    }
}
