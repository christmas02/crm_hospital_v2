<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('file_attente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->time('heure_arrivee');
            $table->integer('position');
            $table->enum('statut', ['en_attente', 'appele', 'termine'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_attente');
    }
};
