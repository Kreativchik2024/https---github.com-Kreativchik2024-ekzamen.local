<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('leagues', fn(Blueprint $t) => $t->renameColumn('sportmonks_id', 'external_id'));
    Schema::table('teams', fn(Blueprint $t) => $t->renameColumn('sportmonks_id', 'external_id'));
    Schema::table('fixtures', fn(Blueprint $t) => $t->renameColumn('sportmonks_id', 'external_id'));
    Schema::table('bookmakers', fn(Blueprint $t) => $t->renameColumn('sportmonks_id', 'external_id'));
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_id', function (Blueprint $table) {
            //
        });
    }
};
