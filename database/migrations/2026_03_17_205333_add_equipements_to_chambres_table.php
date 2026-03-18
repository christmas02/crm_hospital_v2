<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('chambres', function (Blueprint $table) {
            $table->text('equipements')->nullable()->after('tarif_jour');
        });

        // Expand the type enum to include 'suite' (MySQL only, SQLite doesn't support MODIFY)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE chambres MODIFY COLUMN type ENUM('individuelle', 'double', 'vip', 'suite') DEFAULT 'individuelle'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chambres', function (Blueprint $table) {
            $table->dropColumn('equipements');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE chambres MODIFY COLUMN type ENUM('individuelle', 'double', 'vip') DEFAULT 'individuelle'");
        }
    }
};
