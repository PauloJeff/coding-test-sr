<?php

use App\Demand;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', 'AuthController@signup');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('logged', 'AuthController@logged');
    });
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('product', 'ProductController@addProduct');
    Route::post('product/{product}', 'ProductController@updateProduct');
    Route::get('product', 'ProductController@getProducts');
    Route::get('product/delete/{product}', 'ProductController@delete');
    Route::get('product/stockLow', 'ProductController@getProductsLowStock');
    Route::get('bestSeller', 'ProductController@bestSeller');

    Route::post('demand', 'DemandController@addDemand');
    Route::post('demand/{demand}', 'DemandController@updateDemand');
    Route::get('demand/cancel/{demand}', 'DemandController@cancelDemand');
    Route::get('demand', 'DemandController@demand');
    Route::get('averageTicket', 'DemandController@averageTicket');
});