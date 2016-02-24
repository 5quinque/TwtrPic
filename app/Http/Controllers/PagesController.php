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

    public function about() {
        return view('pages.about');
    }

    public function search() {
        $search_term = "Ryan!";

        return view('pages.search', compact('search_term'));
    }
}
