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
            $table->string('type_prise_en_charge')->nullable()->after('notes'); // assurance, mutuelle, indigent, null
            $table->string('organisme_prise_en_charge')->nullable()->after('type_prise_en_charge');
            $table->string('numero_assurance')->nullable()->after('organisme_prise_en_charge');
            $table->integer('taux_couverture')->default(0)->after('numero_assurance'); // 0-100%
            $table->integer('montant_couvert')->default(0)->after('taux_couverture');
            $table->integer('montant_patient')->default(0)->after('montant_couvert'); // part patient
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
            $table->dropColumn([
                'type_prise_en_charge',
                'organisme_prise_en_charge',
                'numero_assurance',
                'taux_couverture',
                'montant_couvert',
                'montant_patient',
            ]);
        });
    }
};
