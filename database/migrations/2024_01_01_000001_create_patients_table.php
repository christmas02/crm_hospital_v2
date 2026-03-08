<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F']);
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('groupe_sanguin', 5)->nullable();
            $table->json('allergies')->nullable();
            $table->date('date_inscription');
            $table->enum('statut', ['actif', 'hospitalise', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
