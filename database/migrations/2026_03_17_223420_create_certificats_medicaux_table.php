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
        Schema::create('certificats_medicaux', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // CERT-YYYYMMDD-0001
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('medecin_id')->constrained();
            $table->foreignId('consultation_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['arret_maladie', 'aptitude', 'inaptitude', 'medical_general', 'deces']);
            $table->date('date_emission');
            $table->date('date_debut')->nullable(); // For arret_maladie
            $table->date('date_fin')->nullable(); // For arret_maladie
            $table->integer('nb_jours')->nullable(); // For arret_maladie
            $table->text('motif');
            $table->text('observations')->nullable();
            $table->text('conclusion')->nullable();
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
        Schema::dropIfExists('certificats_medicaux');
    }
};
