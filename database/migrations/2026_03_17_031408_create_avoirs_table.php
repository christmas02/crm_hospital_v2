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
        Schema::create('avoirs', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('facture_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained();
            $table->integer('montant');
            $table->string('motif');
            $table->text('notes')->nullable();
            $table->enum('statut', ['en_attente', 'applique', 'annule'])->default('en_attente');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('avoirs');
    }
};
