<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalCodeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postal_code_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
                $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger('postal_code_id');
                $table->foreign("postal_code_id")->references("id")->on("postal_codes");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postal_code_user');
    }
}
