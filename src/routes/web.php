<?php

use App\Http\Controllers\SampleController;
use Illuminate\Support\Facades\Route;

# Route::get('/', function () { return view('welcome');});

Route::get('/', [SampleController::class, 'index']);
Route::post('/upload', [SampleController::class, 'upload']);
