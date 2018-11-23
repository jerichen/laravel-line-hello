<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['web']], function () {
    Route::post('line/reply', 'LineController@reply')->name('line.reply');
});

// line-bot
//Route::post('line/reply', 'LineController@reply')->name('line.reply');
//Route::get('line/push', 'LineController@push')->name('line.push');
