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


#user

Route::post('users/register', 'Auth\ApiRegisterController@register')->name('users.register');
Route::post('users/login', 'Auth\ApiAuthController@login')->name('users.login');


Route::get('users', 'Rest\UserController@index')->name('users.list');

Route::post('users/{id}/send-points', 'Rest\UserController@sendPoints')
    ->middleware('api.auth')->name('users.send-points');

Route::post('users/{id}/subscribe', 'Rest\UserController@subscribe')
    ->middleware('api.auth')->name('users.subscribe');

Route::post('users/{id}/unsubscribe', 'Rest\UserController@unsubscribe')
    ->middleware('api.auth')->name('users.unsubscribe');

Route::get('users/{id}/subscribers', 'Rest\UserController@getSubscribers')
    ->name('users.subscribers.show');

Route::get('users/{id}/followers', 'Rest\UserController@getFollowers')
    ->name('users.followers.show');

#articles

Route::resource('articles', 'Rest\ArticlesController')->only([
    'index', 'show'
])->middleware('api.auth.not-required');

Route::post('articles/create', 'Rest\ArticlesController@create')
    ->middleware('api.auth')->name('articles.create');

Route::post('articles/{id}/like', 'Rest\ArticlesController@like')
    ->middleware('api.auth')->name('articles.like');



Route::delete('articles/{id}', 'Rest\ArticlesController@delete')->name('articles.delete');

Route::patch('articles/{id}/disable', 'Rest\ArticlesController@disableArticle')
    ->middleware('api.auth')->name('articles.update');

Route::post('articles/{id}/update', 'Rest\ArticlesController@update')
    ->middleware('api.auth')->name('articles.update');

Route::match(['get', 'post'], 'articles/{id}/buy', 'Rest\ArticlesController@buy')
    ->middleware('api.auth')->name('articles.buy');


#Comments

Route::post('articles/{article_id}/comment/create', 'Rest\CommentController@create')
    ->middleware('api.auth')->name('comment.create');

Route::delete('comment/{comment_id}/delete', 'Rest\CommentController@delete')
    ->middleware('api.auth')->name('comment.delete');