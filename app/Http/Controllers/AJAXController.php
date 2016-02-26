<?php

namespace twtrpic\Http\Controllers;

use Illuminate\Http\Request;

use \twtrpic\Tweets;
use twtrpic\Http\Requests;
use twtrpic\Http\Controllers\Controller;

/**
 * Class AJAXController
 * @package twtrpic\Http\Controllers
 */
class AJAXController extends Controller
{
    /**
     * @param string $search_term
     * @param string $newer_or_older
     * @param int $id
     * @param int $nsfw
     * @return array
     */
    public function update($search_term = "#selfie", $newer_or_older = "olderthan", $id = 0, $nsfw = 0) {
        $no = ($newer_or_older == "newerthan" ? ">" : "<");
        $orderBy = ($newer_or_older == "newerthan" ? "asc" : "desc");

        if ($id == 0) {
            $images = Tweets::where('search_term', $search_term)->whereIn('nsfw', array('0', $nsfw))->orderBy('updated_at', $orderBy)->paginate(80);
        } else {
            $images = Tweets::where('id', $no, $id)->where('search_term', $search_term)->whereIn('nsfw', array('0', $nsfw))->orderBy('updated_at', $orderBy)->paginate(80);
        }

        if (count($images) != 0) {
            $oldest_image = count($images) - 1;
            $oldest_id = $images[$oldest_image]->id;

            return [$oldest_id, $images];
        }

        return [];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search() {
        $search_term = "Ryan!";

        return view('pages.search', compact('search_term'));
    }
}
