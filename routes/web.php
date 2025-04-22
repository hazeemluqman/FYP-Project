<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CheckpointController;
use App\Http\Controllers\CheckpointPassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use Illuminate\Support\Facades\Route;

// Show welcome page
Route::get('/', function () {
    return view('welcome');
});


// No auth middleware, anyone can access these routes now
Route::resource('/rfids', RfidController::class);
Route::resource('/checkpoints', CheckpointController::class);
Route::resource('/activities', ActivityController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');