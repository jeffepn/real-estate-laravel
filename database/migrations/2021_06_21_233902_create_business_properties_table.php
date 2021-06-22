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
            $table->string('property_id', 36);
            $table->foreign('property_id')
                ->references('id')
                ->on('properties');
            $table->string('business_id', 36);
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses');
            $table->double('value', 8, 2)->nullable();
            $table->boolean("status")->default(false);
            $table->primary(['property_id', 'business_id'])->unique();
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
