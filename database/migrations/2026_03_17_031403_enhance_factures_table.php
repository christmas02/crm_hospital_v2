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
        Schema::table('factures', function (Blueprint $table) {
            $table->integer('montant_remise')->default(0)->after('montant'); // Discount
            $table->integer('montant_tva')->default(0)->after('montant_remise'); // TVA/Tax
            $table->integer('montant_net')->default(0)->after('montant_tva'); // Net after discount+tax
            $table->integer('montant_paye')->default(0)->after('montant_net'); // Amount already paid
            $table->integer('montant_restant')->default(0)->after('montant_paye'); // Remaining balance
            $table->text('notes')->nullable()->after('envoye_par');
            $table->string('reference_paiement')->nullable()->after('mode_paiement');
            $table->foreignId('encaisse_par')->nullable()->after('date_paiement')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['encaisse_par']);
            $table->dropColumn(['montant_remise', 'montant_tva', 'montant_net', 'montant_paye', 'montant_restant', 'notes', 'reference_paiement', 'encaisse_par']);
        });
    }
};
