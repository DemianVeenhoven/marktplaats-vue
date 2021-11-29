<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_category', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
                $table->foreign("category_id")->references("id")->on("categories");
            $table->unsignedBigInteger('ad_id');
                $table->foreign("ad_id")->references("id")->on("ads");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_category');
    }
}
