<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fiche_traitement_id')->nullable()->constrained('fiches_traitement')->nullOnDelete();
            $table->date('date');
            $table->integer('montant')->default(0);
            $table->enum('statut', ['en_attente', 'envoyee', 'payee', 'annulee'])->default('en_attente');
            $table->string('envoye_par')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->date('date_paiement')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factures');
    }
};
