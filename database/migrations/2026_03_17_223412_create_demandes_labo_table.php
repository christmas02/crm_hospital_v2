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
        Schema::create('demandes_labo', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('medecin_id')->constrained();
            $table->foreignId('consultation_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_demande');
            $table->enum('statut', ['en_attente', 'preleve', 'en_cours', 'termine', 'annule'])->default('en_attente');
            $table->enum('urgence', ['normal', 'urgent', 'tres_urgent'])->default('normal');
            $table->text('notes_cliniques')->nullable();
            $table->date('date_resultat')->nullable();
            $table->foreignId('realise_par')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('demandes_labo');
    }
};
