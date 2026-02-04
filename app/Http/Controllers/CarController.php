<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Нужно для удаления старых картинок

class CarController extends Controller
{
    /**
     * Главная страница (Каталог с фильтрами)
     */
    public function index(Request $request)
    {
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
            case 'newest': // Явно указали, но можно и default
            default:
                $query->latest(); // Сначала новые добавления
                break;
        }

        $cars = $query->get();

        // Данные для выпадающих списков фильтра
        $brands = Car::select('brand')->distinct()->orderBy('brand')->pluck('brand');
        
        // Модели зависят от выбранной марки (если выбрана)
        $modelsQuery = Car::select('model')->distinct()->orderBy('model');
        if ($request->filled('brand')) {
            $modelsQuery->where('brand', $request->brand);
        }
        $models = $modelsQuery->pluck('model');

        return view('cars.index', compact('cars', 'brands', 'models'));
    }

    /**
     * Форма создания машины
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        Car::create($validatedData);

        return redirect()->route('cars.index')->with('success', 'Машина успешно добавлена!');
    }

    /**
     * Просмотр конкретной машины (ЭТОГО МЕТОДА НЕ ХВАТАЛО)
     */
    public function show(Car $car)
    {
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
     * Обновление данных машины
     */
    public function update(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'year'  => 'required|integer',
            'price' => 'required|numeric',
            'mileage' => 'nullable|integer',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'is_sold' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Если загружаем новое фото, старое можно удалить (по желанию)
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            
            $path = $request->file('image')->store('cars', 'public');
            $validatedData['image'] = $path;
        }

        $car->update($validatedData);

        return redirect()->route('cars.show', $car->id)->with('success', 'Данные обновлены!');
    }

    /**
     * Удаление машины
     */
    public function destroy(Car $car)
    {
        // Удаляем картинку, если она была
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Машина удалена!');
    }
}