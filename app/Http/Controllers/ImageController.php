<?php

namespace twtrpic\Http\Controllers;

use Illuminate\Http\Request;

use \twtrpic\Tweets;
use twtrpic\Http\Requests;
use twtrpic\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function showimage($image_hash) {
        $hash = str_replace('.jpg', '', $image_hash);
        $images = Tweets::where('image_md5_hash', $hash)->first();

        $path = base_path();
        $image_data = file_get_contents( "{$path}/{$images->image_location}" );

        return response($image_data)->header('Content-Type', 'image/jpg');
    }
}
