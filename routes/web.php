<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversionController;

Route::post('/convert', [ConversionController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});
