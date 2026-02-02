<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Home Page
Route::view('/', 'home')->name('home');

// Posts CRUD
Route::resource('posts', PostController::class);
// Contact Page
Route::view('/contact', 'contact')->name('contact');
