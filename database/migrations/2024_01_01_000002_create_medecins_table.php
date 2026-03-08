<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medecins', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('specialite');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('bureau')->nullable();
            $table->enum('statut', ['disponible', 'en_consultation', 'en_operation', 'absent'])->default('disponible');
            $table->integer('tarif_consultation')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medecins');
    }
};
