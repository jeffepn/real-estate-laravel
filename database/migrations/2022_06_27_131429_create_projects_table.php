<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->uuid('id')->primary();
            $table->string('person_id', 36)->nullable()->default(null);
            $table->foreign('person_id')
                ->references('id')
                ->on('people');
            $table->string('slug');
            $table->string('name')->unique();
            $table->text('content');
            $table->timestamps();
        });

        if (env("DB_CONNECTION") != "sqlite") {
            DB::statement("ALTER TABLE projects ADD FULLTEXT fulltextprojects_index (name, content)");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
