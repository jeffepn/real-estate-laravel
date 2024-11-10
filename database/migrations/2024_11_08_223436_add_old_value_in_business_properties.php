<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldValueInBusinessProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_properties', function (Blueprint $table) {
            $table->double('old_value', 10, 2)
                ->nullable()
                ->default(null)
                ->after('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_properties', function (Blueprint $table) {
            $table->dropColumn('old_value');
        });
    }
}
