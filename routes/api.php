<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


#user

Route::post('user/register', 'Auth\ApiRegisterController@register')->name('user.register');
Route::post('user/login', 'Auth\ApiAuthController@login')->name('user.login');


#articles

Route::resource('articles', 'Rest\ArticlesController')->only([
    'index', 'show'
]);

Route::post('articles/like/{id}', 'Rest\ArticlesController@like')->name('articles.like');

Route::post('articles/create', 'Rest\ArticlesController@create')->name('articles.create');

Route::delete('articles/{id}', 'Rest\ArticlesController@delete')->name('articles.delete');

Route::patch('articles/disable/{id}', 'Rest\ArticlesController@disableArticle')->name('articles.disable');

Route::post('articles/update/{id}', 'Rest\ArticlesController@update')->name('articles.update');


#Comments

Route::post('articles/{article_id}/comment/create', 'Rest\ArticlesController@createComment')
    ->middleware('api.auth')->name('comment.create');

Route::delete('comment/{comment_id}/delete', 'Rest\ArticlesController@deleteComment')
    ->middleware('api.auth')->name('comment.delete');