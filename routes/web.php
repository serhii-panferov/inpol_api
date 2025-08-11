<?php

use App\Http\Controllers\InpolLogs;
use Illuminate\Support\Facades\Route;

Route::resource('/logs', InpolLogs::class);

