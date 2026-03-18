<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fiches_approvisionnement', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'validee'])->default('en_attente')->after('montant_total');
            $table->date('date_reception')->nullable()->after('statut');
        });
    }

    public function down()
    {
        Schema::table('fiches_approvisionnement', function (Blueprint $table) {
            $table->dropColumn(['statut', 'date_reception']);
        });
    }
};
