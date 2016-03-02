#!/usr/bin/php

<?php

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => ''

]);

$capsule->bootEloquent();

class toConsumeTweet extends Eloquent {

}

class Tweets extends Eloquent
{

}

toConsumeTweet::chunk(100, function($tweets) {
    foreach ($tweets as $tweet) {

        // Save the image with an md5 hash for the filename
        $hash = @md5_file($tweet->image_url);
        // If the images returns a 404
        // delete the tweet and continue
        if (!$hash) {
            $tweet->delete();
            continue;
        }

        $image = @file_get_contents($tweet->image_url);
        // If the images returns a 404
        // delete the tweet and continue
        if (!$image) {
            $tweet->delete();
            continue;
        }

        // Check if we already has this image in our database
        $md5Check = Tweets::where('image_md5_hash', $hash)->first();

        if (!is_null($md5Check)) {
            $tweet->delete();
            continue;
        }

        $image_location = "images/{$hash[0]}/{$hash[1]}/$hash.jpg";

        file_put_contents(dirname(__FILE__) . "/$image_location", $image);

        $nsfw = preg_match('/(cum|topless|sex|#pelfie|#cock|Dressing room #selfie|#ass|#lesbian|#nsfw|naked|#hentai|slutty|#breasts|horny|nude|masturbation|deepthroat|#teen|#amateur|#sex|#milf|#gonewild|#tit|tits|#pov|dick|boobs|busty|#undies|#fucking|#cumshow|#pussy|titty|nipple|xxx|#bbc|porn|#wank|tittied|lesbian|shemale|tranny)/i', $tweet->text);

        $tw = new Tweets;
        $tw->twitter_id = $tweet->twitter_id;
        $tw->twitter_username = $tweet->twitter_username;
        $tw->text = $tweet->text;
        $tw->image_location = $image_location;
        $tw->image_md5_hash = $hash;
        $tw->search_term = $tweet->search_term;
        $tw->nsfw = $nsfw;

        echo $tw->nsfw . "\n";
        echo $tw->text . "\n";
        echo $tweet->image_url . "\n";
        echo "\n";

        $tw->save();

        $tweet->delete();
    }
});
