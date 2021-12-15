<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => 'prevent'],function() {
    Auth::routes();
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/setting', 'EditUserController@showSetting');
    Route::post('/editData', 'EditUserController@edit')->name('edit');

});
