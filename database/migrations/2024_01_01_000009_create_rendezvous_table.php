<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rendezvous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('heure');
            $table->string('motif');
            $table->enum('statut', ['en_attente', 'confirme', 'annule'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rendezvous');
    }
};
