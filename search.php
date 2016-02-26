<?php

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

use Abraham\TwitterOAuth\TwitterOAuth;

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
//
}

class SearchCollector {
    public function enqueueStatus($status, $query) {
        if ( isset($status->entities->media)) {
            $twt = new toConsumeTweet;

            $twt->twitter_id = $status->id;
            $twt->twitter_username = $status->user->screen_name;
            $twt->text = $status->text;
            $twt->image_url = $status->entities->media[0]->media_url_https;
            $twt->search_term = $query;

            $twt->save();
        }
    }
}

// The OAuth credentials you received when registering your app at Twitter
define("TWITTER_CONSUMER_KEY", env('TWITTER_CONSUMER_KEY'));
define("TWITTER_CONSUMER_SECRET", env('TWITTER_CONSUMER_SECRET'));

// The OAuth data for the twitter account
define("OAUTH_TOKEN", env('OAUTH_TOKEN'));
define("OAUTH_SECRET", env('OAUTH_SECRET'));

$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);

$content = $connection->get('search/tweets', ['q' => 'test search', 'count' => '100']);

$sc = new SearchCollector();

foreach ($content->statuses as $status) {
    $sc->enqueueStatus($status, $content->search_metadata->query);
}
