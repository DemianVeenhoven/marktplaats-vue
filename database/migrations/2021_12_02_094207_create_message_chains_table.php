<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageChainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_chains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
                $table->foreign("ad_id")->references("id")->on("ads")->onDelete('cascade');
            $table->unsignedBigInteger('bidder_id');
                $table->foreign("bidder_id")->references("id")->on("users");
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
        Schema::dropIfExists('message_chains');
    }
}
