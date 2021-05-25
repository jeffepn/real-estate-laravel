<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('business_id', 36);
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses');
            $table->string('address_id', 36);
            $table->foreign('address_id')
                ->references('id')
                ->on('addresses');
            $table->string('slug', 100);
            $table->double('building_area', 8, 2);
            $table->double('total_area', 8, 2);
            $table->string('min_description', 200);
            $table->text('content');
            $table->text('items');
            $table->integer('min_dormitory');
            $table->integer('max_dormitory');
            $table->integer('min_bathroom');
            $table->integer('max_bathroom');
            $table->integer('min_suite');
            $table->integer('max_suite');
            $table->integer('min_garage');
            $table->integer('max_garage');
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
        Schema::dropIfExists('properties');
    }
}
