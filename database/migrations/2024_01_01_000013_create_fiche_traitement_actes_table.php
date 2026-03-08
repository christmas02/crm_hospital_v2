<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fiche_traitement_actes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiche_traitement_id')->constrained('fiches_traitement')->onDelete('cascade');
            $table->foreignId('acte_medical_id')->constrained('actes_medicaux')->onDelete('cascade');
            $table->string('nom');
            $table->integer('prix')->default(0);
            $table->integer('quantite')->default(1);
            $table->boolean('facturable')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiche_traitement_actes');
    }
};
