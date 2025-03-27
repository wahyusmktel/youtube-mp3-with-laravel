<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YouTubeConverterController;

Route::get('/', [YouTubeConverterController::class, 'index']);
Route::post('/convert', [YouTubeConverterController::class, 'convert'])->name('convert');
