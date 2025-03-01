<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tazas', 'alias')) {
            Schema::table('tazas', function (Blueprint $table) {
                $table->string('alias')->nullable(); // Agrega la columna alias
            });
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('tazas', 'alias')) {
            Schema::table('tazas', function (Blueprint $table) {
                $table->dropColumn('alias');
            });
        }
    }
};
