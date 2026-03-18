<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medecins', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('tarif_consultation');
        });
    }

    public function down()
    {
        Schema::table('medecins', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
