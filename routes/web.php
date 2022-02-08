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

        ######################## Diary ########################
        Route::get('/getDiary', 'DiaryController@getLastPage')->name('getDiary');
        Route::get('/getBook', 'DiaryController@retrieveBookmarked')->name('getBooked');
        Route::get('/searchPage', 'DiaryController@searchForPage')->name('searchPage');

        Route::post('/setContent', 'DiaryController@setContent')->name('setContent');
        Route::post('/bookmarkPage', 'DiaryController@bookmarkPage')->name('bookmarkPage');
        Route::post('/deleteDiary', 'DiaryController@deleteDiary')->name('deleteDiary');

        ######################## Notes ########################
        Route::get('/getAllNotes', 'NoteController@getAllNotes')->name('getAllNotes');
        Route::get('/getCategoryNotes', 'NoteController@getCategoryNotes')->name('getCategoryNotes');
        Route::get('/getNotes', 'NoteController@getNotes')->name('getNotes');
        Route::get('/getCategories', 'NoteController@getCategories')->name('getCategories');

        Route::get('/sortNotesByTitle', 'NoteController@sortNotesByTitle')->name('sortNotesByTitle');
        Route::post('/createNoteCategory', 'NoteController@createNoteCategory')->name('createNoteCategory');
        Route::post('/addNote', 'NoteController@addNote')->name('addNote');
        Route::post('/editNote', 'NoteController@editNote')->name('editNote');
        Route::post('/deleteNote', 'NoteController@deleteNote')->name('deleteNote');
        Route::post('/pinNote', 'NoteController@pinNote')->name('pinNote');
        Route::post('/shareNote', 'NoteController@shareNote')->name('shareNote');

        ######################## Tasks ########################
        Route::get('/getAllTasks', 'TaskController@getTasks')->name('getAllTasks');
        Route::get('/getCategoryTasks', 'TaskController@getCategoryTasks')->name('getCategoryTasks');
        Route::get('/getTaskCategories', 'TaskController@getCategories')->name('getTaskCategories');

        Route::get('/sortTasksByTitle', 'TaskController@sortByTitle')->name('sortTasksByTitle');
        Route::get('/sortByDeadline', 'TaskController@sortByDeadline')->name('sortByDeadline');

        Route::post('/createTaskCategory', 'TaskController@createCategory')->name('createTaskCategory');
        Route::post('/addTask', 'TaskController@addTask')->name('addTask');
        Route::post('/editTask', 'TaskController@editTask')->name('editTask');
        Route::post('/shareAsCopy', 'TaskController@shareAsCopy')->name('shareAsCopy');
        Route::post('/shareAsCollaborator', 'TaskController@shareAsCollaborator')->name('shareAsCollaborator');

        Route::post('/pinTask', 'TaskController@setAsPinned')->name('pinTask');
        Route::post('/markAsCompleted', 'TaskController@markAsCompleted')->name('markAsCompleted');
        Route::post('/deleteTask', 'TaskController@deleteTask')->name('deleteTask');
        Route::post('/editTaskCategory', 'TaskController@editCategory')->name('editTaskCategory');

    });

});
