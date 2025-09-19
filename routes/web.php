<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InpolLogs;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/', [HomeController::class, 'index']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/logs', InpolLogs::class);
    Route::resource('posts', PostController::class);

    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::post('posts/{post}/comments', [CommentsController::class, 'store'])->name('comments.store');

});

require __DIR__.'/auth.php';
