<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));
Route::get('/ping', fn () => response()->json(['message' => 'pong']));
