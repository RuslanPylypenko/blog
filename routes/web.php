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

Auth::routes();

Route::get('/articles', 'ArticlesController@index')->name('articles');
Route::get('/articles/{id}', 'ArticlesController@show')
    ->where(['id' => '[0-9]+'])
    ->name('article');

Route::post('/articles/like', 'ArticlesController@like')
    ->name('article-like');

Route::get('/home', 'HomeController@index')->name('home');
