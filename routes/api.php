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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'sub'], function () {
    // 获取平台对应的子账户
    Route::get('account', 'NovelApiController@getSubAccount');
});
// 获取可同步的账号
Route::get('getChannel/datas/{Arr}', 'NovelApiController@checkSyncAccount');
