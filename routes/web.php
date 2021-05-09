<?php

/*-----------------------------------------------------------------------
* LICENCE
-------------------------------------------------------------------------
* <Larakanban>
* Copyright (C) <2021>  <Silas S. da Silva>

* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.

* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.

* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <https://www.gnu.org/licenses/>.
------------------------------------------------------------------------*/


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/home', function () {
    return redirect()->route('tasks.index');
})->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('tasks', 'App\Http\Controllers\TaskController@index')->name('tasks.index');
    Route::post('tasks', 'App\Http\Controllers\TaskController@store')->name('tasks.store');
    Route::put('tasks/sync', 'App\Http\Controllers\TaskController@sync')->name('tasks.sync');
    Route::put('tasks/{task}', 'App\Http\Controllers\TaskController@update')->name('tasks.update');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('statuses', 'App\Http\Controllers\StatusController@store')->name('statuses.store');
    Route::put('statuses', 'App\Http\Controllers\StatusController@update')->name('statuses.update');
});
