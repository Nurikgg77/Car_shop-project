<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage; // Модель для галереи
use App\Models\Banner;   // Модель для слайдера
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Для работы с файлами

class CarController extends Controller
{
    /**
     * Главная страница (Каталог + Фильтры + Баннеры)
     */
    public function index(Request $request)
    {
        // --- 1. ФИЛЬТРАЦИЯ И СОРТИРОВКА ---
        $query = Car::query();

        // Фильтры
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('min_year')) {
            $query->where('year', '>=', $request->min_year);
        }
        if ($request->filled('max_year')) {
            $query->where('year', '<=', $request->max_year);
        }

        // Сортировка
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('year', 'asc');
                break;
            case 'newest':
            default:
                $query->latest(); // Сначала новые
                break;
        }

        $cars = $query->get();

        // --- 2. ДАННЫЕ ДЛЯ ВЫПАДАЮЩИХ СПИСКОВ ---
        $brands = Car::select('brand')->distinct()->orderBy('brand')->pluck('brand');
        
        $modelsQuery = Car::select('model')->distinct()->orderBy('model');
        if ($request->filled('brand')) {
            $modelsQuery->where('brand', $request->brand);
        }
        $models = $modelsQuery->pluck('model');

        // --- 3. РЕКЛАМА (ТОЛЬКО СЛАЙДЕР) ---
        $banners = Banner::where('is_active', true)->latest()->get();
        
        // Убрали переменную $popup

        return view('cars.index', compact('cars', 'brands', 'models', 'banners'));
    }

    /**
     * Форма создания
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Сохранение новой машины
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5000',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5000',
            'year'  => 'required|integer|min:1900|max:'.(date('Y')+1),
            'price' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('cars', 'public');
            $validatedData['image'] = $path;
        }

        $car = Car::create($validatedData);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $galleryPath = $photo->store('cars_gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
        }

        return redirect()->route('cars.index')->with('success', 'Машина успешно добавлена!');
    }

    /**
     * Просмотр машины
     */
    public function show(Car $car)
    {
        $car->load('images');
        return view('cars.show', compact('car'));
    }

    /**
     * Форма редактирования
     */
    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    /**
     * Обновление данных
     */
    public function update(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'image' => 'nullable|image|max:5000',
            'photos.*' => 'image|max:5000',
            'year'  => 'required|integer',
            'price' => 'required|numeric',
            'mileage' => 'nullable|integer',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'is_sold' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $path = $request->file('image')->store('cars', 'public');
            $validatedData['image'] = $path;
        }

        $car->update($validatedData);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $galleryPath = $photo->store('cars_gallery', 'public');
                $car->images()->create(['image_path' => $galleryPath]);
            }
        }

        return redirect()->route('cars.show', $car->id)->with('success', 'Данные обновлены!');
    }

    /**
     * Удаление машины
     */
    public function destroy(Car $car)
    {
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        foreach ($car->images as $galleryImage) {
            Storage::disk('public')->delete($galleryImage->image_path);
        }

        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Машина удалена!');
    }
}