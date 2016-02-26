<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/','PagesController@index');
Route::get('about', 'PagesController@about');

Route::get('search', 'PagesController@search');
Route::get('updategrid/{search_term?}/{newer_or_older?}/{id?}/{nsfw?}', ['uses' => 'AJAXController@update']);

Route::get('image/{image_hash}', ['uses' => 'ImageController@showimage']);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
