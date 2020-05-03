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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){
    Route::namespace('Auth')->group(function(){
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login');
        Route::get('/register','RegisterController@showRegisterForm')->name('register');
        Route::post('/register','RegisterController@register');
        Route::post('/logout','LoginController@logout')->name('logout');
    });
    Route::get('/','AdminController@index')->name('home');
    Route::get('/catagory/add','CatagoryController@add');
    Route::get('/catagory/edit/{id}','CatagoryController@edit');
    Route::get('/catagory/delete/{id}','CatagoryController@delete');

Route::post('/catagory/add','CatagoryController@insert');
Route::post('/catagory/edit','CatagoryController@update');
  });

  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/newArticle','AddArticle@index')->name('newArticle');

  Route::namespace('Auth')->group(function () {
    Route::get('/email/verify','VerifyEmailController@email')->name('emailVerify');
    Route::get('/guest','LoginController@GuestLogin')->name('guest');
    Route::post('/email/verify/otp','VerifyEmailController@otp')->name('emailotp');

    Route::post('/password/reset/send','ForgotPasswordController@send');
    Route::post('/password/reset/verify','ForgotPasswordController@verify');      
});


