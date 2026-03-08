<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hospitalisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('chambre_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->date('date_admission');
            $table->date('date_sortie')->nullable();
            $table->text('motif');
            $table->enum('statut', ['en_cours', 'termine'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hospitalisations');
    }
};
