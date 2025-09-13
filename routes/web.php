<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InpolLogs;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::resource('/logs', InpolLogs::class);
Route::resource('posts', PostController::class);
Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::post('posts/{post}/comments', [CommentsController::class, 'store'])->name('comments.store');
