<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'patients',
        'consultations',
        'dossiers_medicaux',
        'fiches_traitement',
        'ordonnances',
        'factures',
        'hospitalisations',
        'rendezvous',
        'file_attente',
        'planning',
        'medicaments',
        'actes_medicaux',
        'chambres',
        'mouvements_stock',
        'fiches_approvisionnement',
        'transactions',
        'paiements',
        'prescriptions',
    ];

    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->softDeletes();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropSoftDeletes();
            });
        }
    }
};
