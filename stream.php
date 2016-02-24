<?php

require 'vendor/autoload.php';

require 'vendor/fennb/phirehose/lib/Phirehose.php';
require 'vendor/fennb/phirehose/lib/OauthPhirehose.php';

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

class TwitterCollector extends OauthPhirehose {
  /**
   * Enqueue each status
   *
   * @param string $status
   */
  public function enqueueStatus($status)
  {
    $data = json_decode($status, true);
    if (is_array($data) && isset($data['entities']['media'])) {

      $twt = new toConsumeTweet;

      $twt->twitter_id = $data['id'];
      $twt->twitter_username = $data['user']['screen_name'];
      $twt->text = $data['text'];
      $twt->image_url = $data['entities']['media'][0]['media_url_https'];
      $twt->search_term = '#selfie';

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

// Start streaming
$sc = new TwitterCollector(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);

$sc->setLang('en');
$sc->setTrack(array('#selfie'));
$sc->consume();
