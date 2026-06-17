<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medecins', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        // Lier le compte démo medecin@medicare.ci au premier médecin
        $user = DB::table('users')->where('email', 'medecin@medicare.ci')->first();
        if ($user) {
            DB::table('medecins')->where('id', 1)->update(['user_id' => $user->id]);
        }
    }

    public function down()
    {
        Schema::table('medecins', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
