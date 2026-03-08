<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actes_medicaux', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nom');
            $table->string('categorie');
            $table->integer('prix')->default(0);
            $table->boolean('facturable')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actes_medicaux');
    }
};
