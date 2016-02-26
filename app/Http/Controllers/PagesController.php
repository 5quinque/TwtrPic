<?php

namespace twtrpic\Http\Controllers;

use Illuminate\Http\Request;

use \twtrpic\Tweets;
use twtrpic\Http\Requests;
use twtrpic\Http\Controllers\Controller;

/**
 * Class PagesController
 * @package twtrpic\Http\Controllers
 */
class PagesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('pages.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about() {
        return view('pages.about');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search() {
        $search_term = "Ryan!";

        return view('pages.search', compact('search_term'));
    }
}
