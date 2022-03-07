<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'DashboardController@dashboard')->name('harvey');
Route::get('/harvey-pipeline', 'DashboardController@readPipeline')->name('harvey-pipeline');
Route::get('/harvey-project', 'DashboardController@readProject')->name('harvey-project');

Route::post('/unlock-project', 'DashboardController@unlockProject');
Route::post('/lock-project', 'DashboardController@lockProject');

Route::get('/boom', function () {
    throw new Exception('Trigger a sentry error!');
});
