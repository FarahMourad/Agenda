<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/signup', function () {
    return view('signup');
});
Route::get('/signin', function () {
    return view('login');
});
Route::get('/home', function () {
    return view('home');
});
Route::get('/setting', function () {
    return view('setting');
});
