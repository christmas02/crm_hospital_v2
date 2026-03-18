<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultats_labo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_labo_id')->constrained('demandes_labo')->cascadeOnDelete();
            $table->foreignId('examen_labo_id')->constrained('examens_labo');
            $table->string('valeur')->nullable();
            $table->string('unite')->nullable();
            $table->string('valeur_reference')->nullable();
            $table->enum('interpretation', ['normal', 'bas', 'eleve', 'critique'])->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resultats_labo');
    }
};
