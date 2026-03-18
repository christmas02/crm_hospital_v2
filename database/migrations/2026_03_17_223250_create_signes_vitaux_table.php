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
        Schema::create('signes_vitaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('consultation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pris_par')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('temperature', 4, 1)->nullable(); // 37.5
            $table->string('tension_systolique')->nullable(); // 12
            $table->string('tension_diastolique')->nullable(); // 8
            $table->integer('pouls')->nullable(); // battements/min
            $table->integer('frequence_respiratoire')->nullable(); // respirations/min
            $table->integer('saturation_o2')->nullable(); // %
            $table->decimal('poids', 5, 1)->nullable(); // kg
            $table->decimal('taille', 5, 1)->nullable(); // cm
            $table->decimal('imc', 4, 1)->nullable(); // calculated
            $table->integer('glycemie')->nullable(); // mg/dL
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('signes_vitaux');
    }
};
