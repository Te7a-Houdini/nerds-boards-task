<?php

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
    return redirect('/questions');
});

Route::get('questions','QuestionController@index')->name('questions.index');
Route::post('stack-overflow-questions','StackOverflowQuestionController@store')->name('stack-overflow.questions.store');