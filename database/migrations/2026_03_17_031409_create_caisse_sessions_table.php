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
        Schema::create('caisse_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->datetime('ouverture');
            $table->datetime('fermeture')->nullable();
            $table->integer('solde_ouverture')->default(0); // Opening balance
            $table->integer('solde_fermeture')->nullable(); // Closing balance
            $table->integer('total_encaissements')->default(0);
            $table->integer('total_depenses')->default(0);
            $table->text('notes_ouverture')->nullable();
            $table->text('notes_fermeture')->nullable();
            $table->enum('statut', ['ouverte', 'fermee'])->default('ouverte');
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
        Schema::dropIfExists('caisse_sessions');
    }
};
