<?php

use Illuminate\Support\Facades\Auth;
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

// Must come before all routes below, bootstraps Laravel authentication routes
// We disable the registration route here because only admins have access for now
// We also disable resetting passwords for now because we don't have an email server setup currently
Auth::routes([
    'register' => false,
    'reset' => false,
]);

Route::middleware('auth')->group(function () {
    // General
    Route::get('/', 'DashboardController@showDashboard');
    Route::get('/dashboard', 'DashboardController@showDashboard');

    // Projects
    Route::get('/projects/{project}', 'ProjectController@showProject');
    Route::post('/projects/{project}/redeploy', 'ProjectController@redeployProject');
    Route::post('/projects/{project}/unlock', 'ProjectController@unlockProject');
    Route::post('/projects/{project}/lock', 'ProjectController@lockProject');

    // Deployments
    Route::get('/deployments/{id}', 'DeploymentController@showDeployment');

    // Users
    Route::get('/users/{id}', 'UserController@showProfile');
    Route::post('/users/{id}/password', 'UserController@changePassword');
});
