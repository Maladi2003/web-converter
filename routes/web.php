<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConverterController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/process', [ConverterController::class, 'process']);