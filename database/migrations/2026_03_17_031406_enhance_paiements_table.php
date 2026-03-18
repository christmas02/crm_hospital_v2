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
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('numero_recu')->nullable()->after('id'); // Receipt number
            $table->string('reference')->nullable()->after('mode_paiement'); // Payment reference
            $table->text('notes')->nullable()->after('description');
            $table->foreignId('encaisse_par')->nullable()->after('statut')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign(['encaisse_par']);
            $table->dropColumn(['numero_recu', 'reference', 'notes', 'encaisse_par']);
        });
    }
};
