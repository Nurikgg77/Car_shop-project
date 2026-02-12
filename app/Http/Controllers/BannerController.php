<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Список баннеров (для админа)
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('banners.index', compact('banners'));
    }

    // Форма создания
    public function create()
    {
        return view('banners.create');
    }

    // Сохранение
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3000', // Webp тоже можно
            'title' => 'nullable|string|max:100',
            'text'  => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image' => $path,
            'title' => $request->title,
            'text' => $request->text,
            'is_active' => true,
        ]);

        return redirect()->route('banners.index')->with('success', 'Баннер добавлен!');
    }

    // Удаление
    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return back()->with('success', 'Баннер удален!');
    }
    
    // Переключение активности (показать/скрыть)
    public function update(Request $request, Banner $banner)
    {
        // Используем этот метод просто для переключения статуса
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Статус изменен!');
    }
}