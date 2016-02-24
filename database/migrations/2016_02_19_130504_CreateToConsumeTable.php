<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToConsumeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_consume_tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('twitter_id')->unique(); // id
            $table->string('twitter_username'); // user, screen_name
            $table->string('text'); // text
            $table->string('image_url'); // entities, media, [0], media_url_https
            $table->string('search_term');
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
        Schema::drop('to_consume_tweets');
    }
}
