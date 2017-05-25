<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/','HomeController@index');


Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group([
    'middleware' => 'auth',
], function () {

    Route::get('/show','BlogController@toshowtheblog')->name('toshowblog');

    Route::get('/add', 'BlogController@handleReq');
    Route::get('/addcomment','BlogController@comments');

    Route::get('/blog/{id}/{userwhocreated}','BlogController@openit');

    Route::get('/user/{email}','BlogController@openasperuser');

});