<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->integer("postcode");
            $table->tinytext("woonplaats");
            $table->tinytext("alternatieve_schrijfwijzen");
            $table->tinytext("gemeente");
            $table->tinytext("provincie");
            $table->tinytext("netnummer");
            $table->double("latitude");
            $table->double("longitude");
            $table->tinytext("soort");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postal_codes');
    }
}
