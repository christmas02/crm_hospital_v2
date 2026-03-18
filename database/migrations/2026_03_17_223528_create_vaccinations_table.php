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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('vaccin'); // BCG, ROR, DTC, etc.
            $table->string('maladie'); // Tuberculose, Rougeole, etc.
            $table->date('date_administration');
            $table->string('dose')->nullable(); // 1ère dose, 2ème dose, Rappel
            $table->string('lot')->nullable(); // batch number
            $table->string('site_injection')->nullable(); // bras gauche, cuisse droite
            $table->date('prochain_rappel')->nullable();
            $table->foreignId('administre_par')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('vaccinations');
    }
};
