<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->integer('etage');
            $table->enum('type', ['individuelle', 'double', 'vip']);
            $table->integer('capacite');
            $table->integer('tarif_jour')->default(0);
            $table->enum('statut', ['libre', 'occupee', 'maintenance'])->default('libre');
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chambres');
    }
};
