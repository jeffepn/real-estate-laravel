<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('property_id', 36);
            $table->foreign('property_id')
                ->references('id')
                ->on('properties');
            $table->string("way");
            $table->string("alt")->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_properties');
    }
}
