<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('approvisionnement_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiche_approvisionnement_id')->constrained('fiches_approvisionnement')->onDelete('cascade');
            $table->foreignId('medicament_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->integer('quantite');
            $table->integer('prix_unitaire');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvisionnement_lignes');
    }
};
