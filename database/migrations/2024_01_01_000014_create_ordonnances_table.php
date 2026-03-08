<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('numero_retrait')->unique();
            $table->enum('statut_dispensation', ['en_attente', 'prepare', 'remis'])->default('en_attente');
            $table->date('date_preparation')->nullable();
            $table->date('date_remise')->nullable();
            $table->string('remis_a')->nullable();
            $table->text('recommandations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ordonnances');
    }
};
