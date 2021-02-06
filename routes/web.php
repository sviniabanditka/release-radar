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
Route::group(['namespace' => 'App\Http\Controllers'], function() {

    Route::get('/', 'HomeController@showLandingPage')->name('landing.get');
    Route::get('about', 'HomeController@showAboutPage')->name('about.get');

    //AUTH
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'AuthController@getLogin')->name('login.get');
        Route::post('login', 'AuthController@postLogin')->name('login.post');

        Route::get('register', 'AuthController@getRegister')->name('register.get');
        Route::post('register', 'AuthController@postRegister')->name('register.post');

        Route::get('forgot', 'AuthController@getForgotPassword')->name('forgot.get');
        Route::post('forgot', 'AuthController@postForgotPassword')->name('forgot.post');

        Route::get('reset', 'AuthController@getResetPassword')->name('reset.get');
        Route::post('reset', 'AuthController@postResetPassword')->name('reset.post');

        Route::get('logout', 'AuthController@getLogout')->name('logout.get');
        Route::get('activate', 'AuthController@getActivateUser')->name('activate.get');

        Route::group(['middleware' => 'role:user'], function () {
            Route::post('email', 'AuthController@postUpdateEmail')->name('email.post');
            Route::post('password', 'AuthController@postUpdatePassword')->name('password.post');
        });
    });

    //DASHBOARD
    Route::group(['middleware' => 'role:user', 'prefix' => 'dashboard'], function () {

        //USER
        Route::group(['middleware' => 'role:user'], function () {
            Route::get('/', 'HomeController@showDashboardPage')->name('dashboard.get');
        });

        //ADMIN
        Route::group(['middleware' => 'role:admin'], function () {

        });
    });
});

Route::get('test', function() {return view('auth.forgot_success');});
