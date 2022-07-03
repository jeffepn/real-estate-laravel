<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->uuid('id')->primary();
            $table->string('type_person_id', 36);
            $table->foreign('type_person_id')
                ->references('id')
                ->on('type_people');
            $table->string('slug');
            $table->string('name');
            $table->string('bio', 500)->nullable();
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
        Schema::dropIfExists('people');
    }
}
