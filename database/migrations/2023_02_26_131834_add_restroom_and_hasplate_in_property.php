<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestroomAndHasplateInProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('min_restroom')->after('max_garage')->nullable()->default(0);
            $table->integer('max_restroom')->after('min_restroom')->nullable()->default(0);
            $table->boolean('has_plate')->after('embed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['min_restroom', 'max_restroom', 'has_plate']);
        });
    }
}
