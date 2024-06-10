<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Must come before all routes below, bootstraps Laravel authentication routes
// We disable the registration route here because only admins have access for now
// We also disable resetting passwords for now because we don't have an email server setup currently
Auth::routes([
    'register' => false,
    'reset' => false,
]);

Route::middleware('auth')->group(function () {
    // General
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'showDashboard']);
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'showDashboard']);

    // Projects
    Route::get('/projects/{project}', [App\Http\Controllers\ProjectController::class, 'showProject']);
    Route::post('/projects/{project}/redeploy', [App\Http\Controllers\ProjectController::class, 'redeployProject']);
    Route::post('/projects/{project}/unlock', [App\Http\Controllers\ProjectController::class, 'unlockProject']);
    Route::post('/projects/{project}/lock', [App\Http\Controllers\ProjectController::class, 'lockProject']);

    // Deployments
    Route::get('/deployments/{id}', [App\Http\Controllers\DeploymentController::class, 'showDeployment']);

    // Users
    Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'showProfile']);
    Route::post('/users/{id}/password', [App\Http\Controllers\UserController::class, 'changePassword']);
});
