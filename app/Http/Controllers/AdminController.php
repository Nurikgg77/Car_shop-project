<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Banner;

class AdminController extends Controller
{
    // Главная страница админки (Дашборд)
    public function dashboard()
    {
        // Собираем немного статистики
        $totalCars = Car::count();
        $soldCars = Car::where('is_sold', true)->count();
        $totalBanners = Banner::count();
        
        // Берем последние 5 добавленных машин
        $latestCars = Car::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalCars', 'soldCars', 'totalBanners', 'latestCars'));
    }

    // Страница управления машинами (Таблица)
    public function cars()
    {
        $cars = Car::latest()->paginate(10); // Постраничная навигация (по 10 штук)
        return view('admin.cars', compact('cars'));
    }
}