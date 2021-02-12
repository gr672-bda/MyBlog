<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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

//auth
Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

//User
Route::post('user/auth', 'App\Http\Controllers\UserController@Authorization');

Route::post('user/', [UserController::class, 'store']);

Route::group(['prefix' => '/user', 'middleware' => 'auth'], function () {
    Route::get('news', [UserController::class, 'news']);

    Route::get('posts', [UserController::class, 'getPosts']);

    Route::put('{id}/', [UserController::class, 'update']);

    Route::get('{id}', [UserController::class, 'show']);

    Route::get('', [UserController::class, 'index']);

    Route::get('posts', [UserController::class, 'getPosts']);

    Route::get('profile_info/{id}', [UserController::class, 'getProfileInfo']);
});
//Post
Route::group(['prefix' => '/post', 'middleware' => 'auth'], function () {
    Route::get('', [PostController::class, 'index']);

    Route::post('', [PostController::class, 'store']);

    Route::get('{id}', [PostController::class, 'show']);

    Route::put('{id}/', [PostController::class, 'update']);

    Route::delete('{id}', [PostController::class, 'destroy']);
});
//Blacklist
Route::group(['prefix' => '/blacklist', 'middleware' => 'auth'], function () {
    Route::get('', [BlacklistController::class, 'index']);

    Route::post('', [BlacklistController::class, 'store']);

    Route::get('{id}', [BlacklistController::class, 'show']);

    Route::put('{id}', [BlacklistController::class, 'update']);

    Route::delete('{id}', [BlacklistController::class, 'destroy']);
});
//Comment
Route::group(['prefix' => '/comment', 'middleware' => 'auth'], function () {
    Route::get('', [CommentController::class, 'index']);

    Route::post('', [CommentController::class, 'store']);

    Route::get('{id}', [CommentController::class, 'show']);

    Route::put('{id}', [CommentController::class, 'update']);

    Route::delete('{id}', [CommentController::class, 'destroy']);
});
//Subscribe
Route::group(['prefix' => '/subscriber', 'middleware' => 'auth'], function () {
    Route::get('', [SubscribeController::class, 'index']);

    Route::post('', [SubscribeController::class, 'store']);

    Route::get('{id}', [SubscribeController::class, 'show']);

    Route::put('{id}', [SubscribeController::class, 'update']);

    Route::delete('{id}', [SubscribeController::class, 'destroy']);
});
