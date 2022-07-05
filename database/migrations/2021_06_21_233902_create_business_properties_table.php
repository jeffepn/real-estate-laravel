<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_properties', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('id')->primary();
            $table->string('property_id', 36);
            $table->foreign('property_id')
                ->references('id')
                ->on('properties');
            $table->string('business_id', 36);
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses');
            $table->double('value', 10, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->unique(['property_id', 'business_id']);
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
        Schema::dropIfExists('business_properties');
    }
}
