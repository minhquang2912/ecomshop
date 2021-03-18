<?php

use Illuminate\Support\Facades\Route;

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


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/','App\Http\Controllers\FrontProductListController@index');

Route::get('/product/{id}','App\Http\Controllers\FrontProductListController@show')->name('product.view');

Route::get('/category/{name}','App\Http\Controllers\FrontProductListController@allProduct')->name('product.list');

Route::get('/addToCart/{product}','App\Http\Controllers\CartController@addToCart')->name('add.cart');

Route::get('/cart','App\Http\Controllers\CartController@showCart')->name('cart.show');

Route::post('/charge','App\Http\Controllers\CartController@charge')->name('cart.charge');

Route::get('/orders','App\Http\Controllers\CartController@order')->name('order')->middleware('auth');


Route::post('/products/{product}','App\Http\Controllers\CartController@updateCart')->name('cart.update');

Route::post('/product/{product}','App\Http\Controllers\CartController@removeCart')->name('cart.remove');

Route::get('/checkout/{amount}','App\Http\Controllers\CartController@checkout')->name('cart.checkout')->middleware('auth');

Route::get('all/products','App\Http\Controllers\FrontProductListController@moreProducts')->name('more.product');

Route::group(['prefix'=>'auth','middleware'=>['auth','isAdmin']],function(){
	Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

  Route::resource('/category','App\Http\Controllers\CategoryController');
  Route::resource('/subcategory','App\Http\Controllers\SubcategoryController');
  Route::resource('/product','App\Http\Controllers\ProductController');

  Route::get('slider/create','App\Http\Controllers\SliderController@create')->name('slider.create');
  Route::post('slider','App\Http\Controllers\SliderController@store')->name('slider.store');
  Route::get('slider','App\Http\Controllers\SliderController@index')->name('slider.index');
  Route::delete('slider/{id}','App\Http\Controllers\SliderController@destroy')->name('slider.destroy');

  Route::get('users','App\Http\Controllers\UserController@index')->name('user.index');

  //orders
	Route::get('/orders','App\Http\Controllers\CartController@userOrder')->name('order.index');
	Route::get('/orders/{userid}/{orderid}','App\Http\Controllers\CartController@viewUserOrder')->name('user.order');
});

Route::get('subcatories/{id}','App\Http\Controllers\ProductController@loadSubCategories');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


