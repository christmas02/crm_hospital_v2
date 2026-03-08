<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planning', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained()->onDelete('cascade');
            $table->enum('jour', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('debut');
            $table->time('fin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning');
    }
};
