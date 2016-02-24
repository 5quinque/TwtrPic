<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('twitter_id')->unique(); // id
            $table->string('twitter_username'); // user, screen_name
            $table->string('text'); // text
            $table->string('image_location'); // entities, media, [0], media_url_https
            $table->string('image_md5_hash');
            $table->string('search_term');
            $table->boolean('nsfw');
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
        Schema::drop('tweets');
    }
}
