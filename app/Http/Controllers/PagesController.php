<?php

namespace twtrpic\Http\Controllers;

use Illuminate\Http\Request;

use \twtrpic\Tweets;
use twtrpic\Http\Requests;
use twtrpic\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function index() {
        $time = time();

        $images = Tweets::where('nsfw', 0)->orderBy('updated_at', 'desc')->paginate(80);

        $latest_id = $images[0]->id;
        $oldest_id = $images[79]->id;

        return view('pages.index', compact('images', 'time', 'latest_id', 'oldest_id'));
    }

    public function update($search_term = "#selfie", $newer_or_older = "newerthan", $id = 0, $nsfw = 0) {
        $no = ($newer_or_older == "newerthan" ? ">" : "<");
        $orderBy = ($newer_or_older == "newerthan" ? "asc" : "desc");

        $images = Tweets::where('id', $no, $id)->where('search_term', $search_term)->whereIn('nsfw', array('0', $nsfw))->orderBy('updated_at', $orderBy)->paginate(80);

        if (count($images) != 0) {
            $latest_id = $images[0]->id;

            $oldest_image = count($images) - 1;
            $oldest_id = $images[$oldest_image]->id;

            return [$latest_id, $oldest_id, $images];
        }

        return [];
    }

    public function about() {
        return view('pages.about');
    }

    public function search() {
        $search_term = "Ryan!";

        return view('pages.search', compact('search_term'));
    }
}
