<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController; // Не забудь подключить!
use Illuminate\Support\Facades\Route;

// --- ПУБЛИЧНЫЕ МАРШРУТЫ (видят все) ---

// Главная - список машин
Route::get('/', [CarController::class, 'index'])->name('home');

// Просмотр списка и конкретной машины
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Маршруты авторизации
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- ЗАЩИЩЕННЫЕ МАРШРУТЫ (только для Админа) ---
// Группа маршрутов с middleware 'auth'
Route::middleware(['auth'])->group(function () {
    
    // Создание
    Route::get('/cars/create/new', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    
    // Редактирование
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    
    // Удаление
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
});