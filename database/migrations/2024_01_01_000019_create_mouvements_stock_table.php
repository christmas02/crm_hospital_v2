<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicament_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entree', 'sortie']);
            $table->integer('quantite');
            $table->date('date');
            $table->string('motif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
