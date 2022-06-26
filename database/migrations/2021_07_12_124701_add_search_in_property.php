<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSearchInProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env("DB_CONNECTION") != "sqlite") {
            DB::statement("ALTER TABLE properties ADD FULLTEXT fulltextproperties_index (min_description, content)");
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
            Schema::table('properties', function ($table) {
                $table->dropIndex('fulltextproperties_index');
            });
        }
    }
}
