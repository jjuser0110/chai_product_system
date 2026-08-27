<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/master_setting')->as('master_setting.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'MasterController@index')->name('index');
    Route::get('/create', 'MasterController@create')->name('create');
    Route::post('/store', 'MasterController@store')->name('store');
    Route::get('/edit/{master_setting}', 'MasterController@edit')->name('edit');
    Route::post('/update/{master_setting}', 'MasterController@update')->name('update');
    Route::get('/destroy/{master_setting}', 'MasterController@destroy')->name('destroy');
});
