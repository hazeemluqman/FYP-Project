<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\CheckpointController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Auth;

// Show welcome page
Route::get('/', function () {
    return view('welcome2');
});

// Authentication routes
Auth::routes();

// Redirect /home to /checkpoints
Route::get('/home', function () {
    return redirect('/checkpoints');
})->name('home');

// Protected routes - only accessible to logged-in users
Route::middleware(['auth'])->group(function () {
    Route::resource('/rfids', RfidController::class);
    Route::resource('/checkpoints', CheckpointController::class);
    Route::resource('/activities', ActivityController::class); 
    Route::resource('/profile', ProfileController::class); 
    Route::resource('/accounts', UserAccountController::class); 
    Route::resource('/activity', ActivityController::class);
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/download', [App\Http\Controllers\ReportController::class, 'downloadPdf'])->name('reports.download');
    Route::put('/users/{user}/role', [UserAccountController::class, 'updateRole'])->name('users.updateRole');
    Route::view('/about', 'about.about')->name('about');
});