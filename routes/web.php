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
    return redirect()->to('home');
});
Route::get('/error', function () {
    return view('error');
})->name('error');
Auth::routes();

Route::prefix('/admin')->namespace('Admin')->group(function(){
    Route::group(['middleware' => 'isAdmin'], function () {
        Route::get('/dashboard','AdminController@index')->name('admin.home');
        Route::get('/dashboard/{table}','AdminController@index')->name('admin.tables');
        Route::get('/catagory/add','CatagoryController@add')->name('admin.catagory');
        Route::get('/catagory/edit/{id}','CatagoryController@edit');
        Route::get('/catagory/delete/{id}','CatagoryController@delete');
        Route::post('/catagory/add','CatagoryController@insert');
        Route::post('/catagory/edit','CatagoryController@update');

        Route::get('/user/update/{id}','AdminUserController@userStatusUpdate');
        Route::post('/user/update','AdminUserController@userUpdate');
        Route::get('/user/view/{id}','AdminUserController@userView')->name('admin.userView');
        Route::get('/article/update/{id}','AdminArticleController@articleUpdate')->name('admin.article.status');
        Route::get('/article/delete/{id}','AdminArticleController@articleDelete')->name('admin.article.delete');
    });
    Route::namespace('Auth')->group(function(){
        Route::get('/login','LoginController@showLoginForm')->name('admin.login');
        Route::post('/login','LoginController@login');
        Route::post('/logout','LoginController@logout')->name('admin.logout');
    });
  });
  Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', function(){
        return view('user.profile',['profile'=>Auth::user()]);
    })->name('profile');
    Route::get('/profile', function(){
        return view('user.profile',['profile'=>Auth::user()]);
    })->name('profile');
    Route::get('/home/catagory/{id}', 'HomeController@catagory')->name('catagory');
    Route::get('/myArticle','HomeController@myArticle')->name('myArticle');
    Route::get('/user/{id}/articles','HomeController@userArticles')->name('userArticles');
    Route::post('/user/update','HomeController@userUpdate');
    Route::prefix('/article')->namespace('Article')->group(function(){
        Route::get('/get/{id}', 'ArticleController@index')->name('article');
        Route::get('/edit/{id}', 'ArticleController@editForm');
        Route::get('/delete/{id}', 'ArticleController@delete');
        Route::get('/like/{id}', 'ArticleController@like');
        Route::post('/comment', 'ArticleController@comment');
        Route::get('/comment/update/{id}','CommentController@updateStatus');
        Route::post('/edit', 'ArticleController@edit')->name('editArticle');
        Route::post('/new', 'ArticleController@addPost')->name('postArticle');
        Route::get('/new','ArticleController@getAddForm')->name('newArticle');
    });
  
  });


Route::namespace('Auth')->group(function () {
    Route::get('/email/verify','VerifyEmailController@email')->name('emailVerify');
    Route::get('/mobile/verify','VerifyMobileController@index')->name('mobileVerify');
    Route::get('/guest','LoginController@GuestLogin')->name('guest');
    Route::post('/email/verify/otp','VerifyEmailController@otp')->name('emailotp');
    Route::post('/mobile/verify/otp','VerifyMobileController@verifyOtp')->name('mobileotp');
    Route::post('/password/reset/send','ForgotPasswordController@send');
    Route::post('/password/reset/verify','ForgotPasswordController@verify');      
});


