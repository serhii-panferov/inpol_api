<?php

use App\Http\Controllers\InpolLogs;
use Illuminate\Support\Facades\Route;

Route::get('/logs', [InpolLogs::class, 'index']);

