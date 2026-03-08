<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fiches_approvisionnement', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->date('date');
            $table->string('fournisseur');
            $table->integer('total_articles')->default(0);
            $table->integer('total_quantite')->default(0);
            $table->integer('montant_total')->default(0);
            $table->text('observations')->nullable();
            $table->string('cree_par')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiches_approvisionnement');
    }
};
