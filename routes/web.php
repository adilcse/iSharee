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
Route::get('/error', function () {
    return view('error');
})->name('error');
Auth::routes();

Route::prefix('/admin')->namespace('Admin')->group(function(){
    Route::group(['middleware' => 'isAdmin'], function () {
        Route::get('/','AdminController@index')->name('admin.home');
        Route::get('/','AdminController@profile')->name('admin.profile');
        Route::get('/catagory/add','CatagoryController@add')->name('admin.catagory');
        Route::get('/catagory/edit/{id}','CatagoryController@edit');
        Route::get('/catagory/delete/{id}','CatagoryController@delete');
        Route::post('/catagory/add','CatagoryController@insert');
        Route::post('/catagory/edit','CatagoryController@update');
    });
    Route::namespace('Auth')->group(function(){
        Route::get('/login','LoginController@showLoginForm')->name('admin.login');
        Route::post('/login','LoginController@login');
        Route::post('/logout','LoginController@logout')->name('admin.logout');
    });
  });
  Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/article/{id}', 'ArticleController@index')->name('article');
    Route::post('/newArticle', 'ArticleController@addPost')->name('postArticle');
    Route::get('/newArticle','ArticleController@getAddForm')->name('newArticle');
  
  });

Route::namespace('Auth')->group(function () {
    Route::get('/email/verify','VerifyEmailController@email')->name('emailVerify');
    Route::get('/guest','LoginController@GuestLogin')->name('guest');
    Route::post('/email/verify/otp','VerifyEmailController@otp')->name('emailotp');
    Route::post('/password/reset/send','ForgotPasswordController@send');
    Route::post('/password/reset/verify','ForgotPasswordController@verify');      
});


