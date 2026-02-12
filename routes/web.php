<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\PopupController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. ПУБЛИЧНАЯ ЧАСТЬ (Видят ВСЕ)
// ==========================================

// Главная страница (Каталог)
Route::get('/', [CarController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Страница входа
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// 2. АДМИН ПАНЕЛЬ (ТОЛЬКО ПОСЛЕ ВХОДА)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Главная страница админки (Дашборд)
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Управление машинами (Таблица)
    Route::get('/cars-list', [AdminController::class, 'cars'])->name('admin.cars');

    // CRUD Операции (Создание, Редактирование, Удаление)
    // Важно: Эти маршруты теперь внутри защиты!
    Route::get('/cars/create/new', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

    // Управление Баннерами и Попапами
    Route::resource('banners', BannerController::class);
    Route::resource('popups', PopupController::class);

});