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
        Schema::create('remboursements', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // RMB-YYYYMMDD-0001
            $table->foreignId('paiement_id')->constrained();
            $table->foreignId('facture_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->integer('montant');
            $table->string('motif');
            $table->string('mode_remboursement'); // especes, carte, virement
            $table->text('notes')->nullable();
            $table->enum('statut', ['en_attente', 'effectue', 'annule'])->default('en_attente');
            $table->foreignId('effectue_par')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('remboursements');
    }
};
