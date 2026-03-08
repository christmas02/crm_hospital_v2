<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facture_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantite')->default(1);
            $table->integer('prix_unitaire')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facture_lignes');
    }
};
