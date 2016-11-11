<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middlewareGroups' => ['web']], function () {
	
	Route::any('/admin', array('as' => 'admin', 'uses' => 'UsersController@adminLoginForm'));
        Route::post('/login', array('as' => 'login', 'uses' => 'UsersController@adminLoginCheck'));
        Route::get('/logout', array('as' => 'logout', 'uses' => 'UsersController@getLogOut'));
        
        // Dashboard
        Route::any('/adminDashboard', array('as' => 'adminDashboard', 'uses' => 'UsersController@adminDashboard'));
        
        // Update user profile
        Route::get('/updateUserProfileForm', array('as' => 'updateUserProfileForm', 'uses' => 'UsersController@updateUserProfileForm'));
        Route::post('/updateUserProfile', array('as' => 'updateUserProfile', 'uses' => 'UsersController@updateUserProfile'));
});