<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Nullifier les emails dupliqués avant d'ajouter la contrainte unique
        $duplicates = DB::table('patients')
            ->select('email', DB::raw('MIN(id) as keep_id'))
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('patients')
                ->where('email', $duplicate->email)
                ->where('id', '!=', $duplicate->keep_id)
                ->update(['email' => null]);
        }

        Schema::table('patients', function (Blueprint $table) {
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }
};
