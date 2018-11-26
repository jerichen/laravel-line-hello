<?php
Route::get('/', 'IndexController@index')->name('index');

// line-bot
Route::post('line/reply', 'LineController@reply')->name('line.reply');
Route::get('line/push', 'LineController@push')->name('line.push');

Route::post('webhook', 'LineController@webhook')->name('webhook');
