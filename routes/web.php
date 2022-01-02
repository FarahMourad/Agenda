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

        Route::get('/test', function (){
            return view('test');
        });

        Route::get('/setting', 'EditUserController@showSetting');
        Route::post('/editData', 'EditUserController@edit')->name('edit');
        Route::post('/setTheme', 'EditUserController@editTheme')->name('edit-theme');

        ########################Diary########################
        Route::get('/getDiary', 'DiaryController@getLastPage')->name('getDiary');
        Route::get('/getBook', 'DiaryController@retrieveBookmarked')->name('getBooked');
        Route::get('/searchPage', 'DiaryController@searchForPage')->name('searchPage');

        Route::post('/setContent', 'DiaryController@setContent')->name('setContent');
        Route::post('/bookmarkPage', 'DiaryController@bookmarkPage')->name('bookmarkPage');
        Route::post('/deleteDiary', 'DiaryController@deleteDiary')->name('deleteDiary');


    });

});
