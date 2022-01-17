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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('register', 'JWTAuthController@register');
    Route::post('login', 'JWTAuthController@login');
    Route::get('logout', 'JWTAuthController@logout');
    Route::post('refresh', 'JWTAuthController@refresh');
    Route::post('forgot-password', 'JWTAuthController@forgotPassword');
});
Route::group(['middleware' => 'api','prefix' => 'ajax'], function ($router) {

});
Route::group(['middleware' => ['jwt.verify']], function() {
  Route::get('profile', 'JWTAuthController@profile');
  Route::put('profile/update', 'JWTAuthController@update');
  Route::put('change-password', 'JWTAuthController@changePassword');
  Route::put('set/notification', 'JWTAuthController@setNotification');
  Route::get('check-background', 'JWTAuthController@checkBackground');
  Route::put('set/email', 'JWTAuthController@setEmail');

  Route::post('site/nearby-list', 'Api\SiteController@nearByList');
  Route::post('order/add', 'Api\OrderController@add');
  Route::put('order/update', 'Api\OrderController@update');
  Route::put('order/cancel', 'Api\OrderController@cancel');
  Route::post('order/get-user-order', 'Api\OrderController@getUserOrder');
  Route::post('order/get-order-number', 'Api\OrderController@getOrderNumber');
  Route::post('order/my-orders/{type}', 'Api\OrderController@myOrders');
  Route::delete('order/delete/{id}', 'Api\OrderController@delete');
  Route::get('company/menu-qr/{id}', 'Api\OrderController@getMenuImages');

  Route::post('order/find-order-by-number', 'Api\OrderController@findbyOrderNumber');
  Route::get('order/get-latest-order/{uid}', 'Api\OrderController@getLatestOrder');
  Route::delete('delete-account/{uid}', 'JWTAuthController@deleteAccount');

  /*****************Version 2*********************/
  Route::get('restaurant/list', 'Api\SiteController@restaurantList')->name('sites.restaurantList');
  Route::get('restaurant/categories/{id}', 'Api\SiteController@restaurantCategories')->name('sites.restaurantCategories');
  Route::get('restaurant/search-by-name', 'Api\SiteController@restaurantSearchByName')->name('sites.restaurantSearchByName');

  Route::get('menu/list', 'Api\MenuController@listing')->name('menu.listing');
  Route::get('category/item/search-by-name', 'Api\CategoryController@searchByName');
  Route::get('category/detail/{id}', 'Api\CategoryController@detail')->name('category.detail');
  Route::post('cart/add', 'Api\CartController@add');
  Route::put('cart/update/{id}', 'Api\CartController@update');
  Route::get('cart/detail/{uid}', 'Api\CartController@detail');
  Route::delete('cart/delete/{id}', 'Api\CartController@delete');
  Route::delete('cart/clear-restaurant-items/{uid}', 'Api\CartController@clearRestaurantItems');
  Route::post('cart/calculation/{uid}/{itemId}/{freeItems}/{quantity}', 'Api\CartController@calculation');

  Route::get('extras/listing', 'Api\ExtraController@listing');
  Route::get('items/quantity-listing', 'Api\ExtraController@quantityListing');

  Route::post('payment/save', 'Api\PaymentController@save');

  Route::get('card/listing/{uid}', 'Api\CardController@listing');
  Route::post('card/add', 'Api\CardController@add');
  Route::delete('card/delete/{id}', 'Api\CardController@delete');
  Route::get('card/set-default/{id}', 'Api\CardController@setDefault');

  Route::put('order/add-spot-number/{oid}', 'Api\OrderController@addSpotNumber');
  Route::put('order/add-spot-color/{oid}', 'Api\OrderController@addSpotColor');

  Route::post('order/set-user-locate', 'Api\OrderController@setUserLocate');
  /*****************Version 2*********************/
});
