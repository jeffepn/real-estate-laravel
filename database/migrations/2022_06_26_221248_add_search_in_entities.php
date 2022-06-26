<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSearchInEntities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env("DB_CONNECTION") != "sqlite") {
            DB::statement("ALTER TABLE businesses ADD FULLTEXT fulltextbusinesses_index (name)");
            DB::statement("ALTER TABLE types ADD FULLTEXT fulltexttypes_index (name)");
            DB::statement("ALTER TABLE sub_types ADD FULLTEXT fulltextsub_types_index (name)");
            DB::statement("ALTER TABLE situations ADD FULLTEXT fulltextsituations_index (name)");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env("DB_CONNECTION") != "sqlite") {
            Schema::table('businesses', function ($table) {
                $table->dropIndex('fulltextbusinesses_index');
            });
            Schema::table('types', function ($table) {
                $table->dropIndex('fulltexttypes_index');
            });
            Schema::table('sub_types', function ($table) {
                $table->dropIndex('fulltextsub_types_index');
            });
            Schema::table('situations', function ($table) {
                $table->dropIndex('fulltextsituations_index');
            });
        }
    }
}
