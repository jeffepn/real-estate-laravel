<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbnailInImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_properties', function (Blueprint $table) {
            $table->string('thumbnail')->after('way')->nullable()->default(null);
        });

        Schema::table('images_realestate', function (Blueprint $table) {
            $table->string('thumbnail')->after('way')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_properties', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
        });

        Schema::table('images_realestate', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
        });
    }
}
