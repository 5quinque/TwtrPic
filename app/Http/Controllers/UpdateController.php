<?php

namespace twtrpic\Http\Controllers;

use Illuminate\Http\Request;

use \twtrpic\Tweets;
use twtrpic\Http\Requests;
use twtrpic\Http\Controllers\Controller;

class UpdateController extends Controller
{
    public function update($search_term = "#selfie", $newer_or_older = "newerthan", $id = 0, $nsfw = 0) {
        $no = ($newer_or_older == "newerthan" ? ">" : "<");
        $images = Tweets::where('id', $no, $id)->where('search_term', $search_term)->whereIn('nsfw', array('0', $nsfw))->orderBy('updated_at', 'asc')->paginate(80);

        return $images;
    }
}
