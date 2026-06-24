<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReceitaController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware([VerifyCsrfToken::class]);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->withoutMiddleware([VerifyCsrfToken::class]);

Route::resource('receitas', ReceitaController::class);
