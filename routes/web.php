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

Route::get('/email/verify','Auth\VerifyEmailController@email')->name('emailVerify');
Route::get('/guest','Auth\LoginController@GuestLogin')->name('guest');
Route::post('/email/verify/otp','Auth\VerifyEmailController@otp')->name('emailotp');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/newArticle','AddArticle@index')->name('newArticle');

Route::post('/password/reset/send','Auth\ForgotPasswordController@send');
Route::post('/password/reset/verify','Auth\ForgotPasswordController@verify');

Route::get('/admin/catagory/add','Admin\CatagoryController@add');
Route::get('/admin/catagory/edit/{id}','Admin\CatagoryController@edit');
Route::get('/admin/catagory/delete/{id}','Admin\CatagoryController@delete');

Route::post('/admin/catagory/add','Admin\CatagoryController@insert');
Route::post('/admin/catagory/edit','Admin\CatagoryController@update');