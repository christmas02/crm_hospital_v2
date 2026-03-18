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
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // REF-YYYYMMDD-0001
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('medecin_referent_id')->constrained('medecins'); // doctor who refers
            $table->foreignId('medecin_cible_id')->constrained('medecins'); // specialist
            $table->foreignId('consultation_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_reference');
            $table->string('motif');
            $table->text('contexte_clinique')->nullable(); // clinical context
            $table->text('examens_joints')->nullable(); // attached exams
            $table->enum('urgence', ['normal', 'urgent', 'tres_urgent'])->default('normal');
            $table->enum('statut', ['en_attente', 'acceptee', 'consultation_faite', 'refusee'])->default('en_attente');
            $table->text('reponse_specialiste')->nullable(); // specialist response
            $table->date('date_consultation_specialiste')->nullable();
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
        Schema::dropIfExists('references');
    }
};
