<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jeffpereira\RealEstate\Models\Property\Property;
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
        $situation = Situation::whereSlug('pronto')->first();
        if ($situation && Property::whereSituationId($situation->id)->count() < 1) {
            $situation->delete();
        }
        Schema::table('properties', function (Blueprint $table) {
            $table->string('situation_id')->default(null)->change();
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
            $table->string('situation_id')->default($situation->id)->change();
        });
    }
}
