<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => 'prevent'],function() {
    Auth::routes();
    Route::group(['middleware' => 'auth'],function() {
        Route::get('/home', function (){
            return view('layouts.home');
        });
        Route::get('/setting', 'EditUserController@showSetting');
        Route::post('/editData', 'EditUserController@edit')->name('edit');
        Route::post('/setTheme', 'EditUserController@editTheme')->name('edit-theme');
    });

});
