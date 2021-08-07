<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jeffpereira\RealEstate\Models\Property\Situation;

class AddSituationInProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $situation = Situation::firstOrCreate(['name' => 'Pronto']);
        Schema::table('properties', function (Blueprint $table) use ($situation) {
            $table->string('situation_id')->nullable()->default($situation->id)->after('sub_type_id');
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
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign('properties_situation_id_foreign');
            $table->dropColumn('situation_id');
        });
    }
}
