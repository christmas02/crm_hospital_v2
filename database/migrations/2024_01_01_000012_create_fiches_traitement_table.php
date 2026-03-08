<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fiches_traitement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('diagnostic')->nullable();
            $table->text('observations')->nullable();
            $table->integer('total_facturable')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiches_traitement');
    }
};
