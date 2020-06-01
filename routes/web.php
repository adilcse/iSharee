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
//default home route
Route::get('/', function () {
    return redirect()->to('home');
});

//error page route
Route::get('/error', function () {
    return view('error');
})->name('error');

//register login routes
Auth::routes();

//google auth login
Route::get('/google-login','Auth\GoogleLoginController@googleLogin')->name('google-login');
Route::get('/login/google','Auth\GoogleLoginController@googleLoginCallback');

//group of all admin routes
Route::prefix('/admin')->namespace('Admin')->middleware('isAdmin')->group(function(){
    Route::get('/dashboard','AdminController@index')->name('admin.home');
    Route::get('/dashboard/{table}','AdminController@index')->name('admin.tables');
    Route::get('/category/add','CategoryController@add')->name('admin.category');
    Route::get('/category/edit/{id}','CategoryController@edit');
    Route::get('/category/delete/{id}','CategoryController@delete');
    Route::get('/user/update/{id}','AdminUserController@userStatusUpdate');
    Route::get('/user/view/{id}','AdminUserController@userView')->name('admin.userView');
    Route::get('/article/update/{id}','AdminArticleController@articleUpdate')->name('admin.article.status');
    Route::get('/article/delete/{id}','AdminArticleController@articleDelete')->name('admin.article.delete');
    Route::post('/category/add','CategoryController@insert');
    Route::post('/category/edit','CategoryController@update');
    Route::post('/user/update','AdminUserController@userUpdate');
});

//all routes of authenticated users
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', function(){
        return view('user.profile',['profile'=>Auth::user()]);
    })->name('profile');
    Route::get('/home/category/{id}', 'HomeController@category')->name('category');
    Route::get('/myArticle','HomeController@myArticle')->name('myArticle');
    Route::get('/user/{id}/articles','HomeController@userArticles')->name('userArticles');
    Route::post('/user/update','HomeController@userUpdate');

    //user article releted routes
    Route::prefix('/article')->namespace('Article')->group(function(){
        Route::get('/get/{id}', 'ArticleController@index')->name('article');
        Route::get('/edit/{id}', 'ArticleController@editForm');
        Route::get('/delete/{id}', 'ArticleController@delete');
        Route::get('/like/{id}', 'ArticleController@like');
        Route::get('/comment/update/{id}','CommentController@updateStatus');
        Route::get('/new','ArticleController@getAddForm')->name('newArticle');
        Route::get('/payment','PaymentController@index')->name('articlePaymentPage');
        Route::post('/edit', 'ArticleController@edit')->name('editArticle');
        Route::post('/new', 'ArticleController@addPost')->name('postArticle');
        Route::post('/payment','PaymentController@payment')->name('stripe.post');
        Route::post('/comment', 'CommentController@articleComment');
    });
});

//verification routes
Route::namespace('Auth')->group(function () {
    Route::get('/email/verify','VerifyEmailController@email')->name('emailVerify');
    Route::get('/mobile/verify','VerifyMobileController@index')->name('mobileVerify');
    Route::get('/guest','LoginController@guestLogin')->name('guest');
    Route::post('/email/verify/otp','VerifyEmailController@otp')->name('emailotp');
    Route::post('/mobile/verify/otp','VerifyMobileController@verifyOtp')->name('mobileotp');
    Route::post('/password/reset/send','ForgotPasswordController@send');
    Route::post('/password/reset/verify','ForgotPasswordController@verify');      
});