<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// // Home Page
Route::view('/', 'home')->name('home');

// Language Translation locale
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'de', 'hi', 'hu', 'it'], true), 404);

    session(['locale' => $locale]);
    cookie()->queue('locale', $locale, 60 * 24 * 30);

    return back();
})->name('lang.switch');



// AJAX routes first
Route::get('/posts/data', [PostController::class, 'getData'])->name('posts.data');
Route::post('/posts/status-update', [PostController::class, 'statusUpdate'])->name('posts.status.update');
Route::post('/posts/delete-ajax', [PostController::class, 'deleteAjax'])->name('posts.delete.ajax');

// Resource routes
Route::resource('posts', PostController::class);

// Contact Page
Route::view('/contact', 'contact')->name('contact');

// Register
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register.form');
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register.store');


require __DIR__.'/auth.php';

