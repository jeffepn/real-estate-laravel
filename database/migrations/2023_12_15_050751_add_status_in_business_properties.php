<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;

class AddStatusInBusinessProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_properties', function (Blueprint $table) {
            $table->tinyInteger('status_situation')
                ->default(BusinessPropertySituationEnum::IN_PROGRESS)
                ->after('status');
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
            $table->dropColumn('status_situation');
        });
    }
}
