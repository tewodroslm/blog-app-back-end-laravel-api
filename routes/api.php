<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'AuthController@register');

Route::post('/login', 'AuthController@login');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post("/create-content", "ContentController@createContent");
    Route::get("/contents", "ContentController@allContents");
    Route::get("/owned-content", "ContentController@ownedPosts");
    Route::delete("/content/{content_id}", "ContentController@deleteContent");
    Route::post("/logout", "AuthController@logout");
});

