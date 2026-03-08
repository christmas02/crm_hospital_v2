<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('categorie');
            $table->string('forme');
            $table->string('dosage')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_min')->default(0);
            $table->integer('prix_unitaire')->default(0);
            $table->string('fournisseur')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicaments');
    }
};
