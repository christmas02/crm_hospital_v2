<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('facture_id')->nullable()->constrained()->nullOnDelete();
            $table->datetime('date_paiement');
            $table->integer('montant');
            $table->string('type');
            $table->string('description')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->enum('statut', ['paye', 'en_attente', 'annule'])->default('paye');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
