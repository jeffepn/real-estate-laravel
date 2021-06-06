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
            $table->engine = "InnoDB";
            $table->uuid('id')->primary();
            $table->string('business_id', 36);
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses');
            $table->string('address_id', 36);
            $table->foreign('address_id')
                ->references('id')
                ->on('addresses');
            $table->string('sub_type_id', 36);
            $table->foreign('sub_type_id')
                ->references('id')
                ->on('sub_types');
            $table->string('slug', 150)->unique();
            $table->double('building_area', 8, 2)->nullable();
            $table->double('total_area', 8, 2)->nullable();
            $table->string('min_description', 200);
            $table->text('content')->nullable();
            $table->text('items')->nullable();
            $table->integer('min_dormitory')->nullable();
            $table->integer('max_dormitory')->nullable();
            $table->integer('min_bathroom')->nullable();
            $table->integer('max_bathroom')->nullable();
            $table->integer('min_suite')->nullable();
            $table->integer('max_suite')->nullable();
            $table->integer('min_garage')->nullable();
            $table->integer('max_garage')->nullable();
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
