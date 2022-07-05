<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jeffpereira\RealEstate\Models\Property\Situation;

class UpdateSituationInProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['situation_id']);
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->string('situation_id')->default(null)->change();
            $table->foreign('situation_id')
                ->references('id')
                ->on('situations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $situation = Situation::firstOrCreate(['name' => 'Pronto']);
        Schema::table('properties', function (Blueprint $table) use ($situation) {
            $table->dropForeign(['situation_id']);
        });
        Schema::table('properties', function (Blueprint $table) use ($situation) {
            $table->string('situation_id')->default($situation->id)->change();
            $table->foreign('situation_id')
                ->references('id')
                ->on('situations');
        });
    }
}
