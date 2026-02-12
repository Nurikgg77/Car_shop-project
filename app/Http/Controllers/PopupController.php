<?php

namespace App\Http\Controllers;

use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    // Список попапов (Админка)
    public function index()
    {
        $popups = Popup::latest()->get();
        return view('popups.index', compact('popups'));
    }

    // Страница добавления
    public function create()
    {
        return view('popups.create');
    }

    // Сохранение
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5000', // Картинка до 5МБ
        ]);

        $path = $request->file('image')->store('popups', 'public');

        // Отключаем все старые попапы, чтобы активным был только один (опционально)
        // Popup::query()->update(['is_active' => false]);

        Popup::create([
            'image' => $path,
            'is_active' => true
        ]);

        return redirect()->route('popups.index')->with('success', 'Реклама добавлена!');
    }

    // Удаление
    public function destroy(Popup $popup)
    {
        if ($popup->image) {
            Storage::disk('public')->delete($popup->image);
        }
        $popup->delete();

        return back()->with('success', 'Удалено!');
    }

    // Вкл/Выкл
    public function update(Request $request, Popup $popup)
    {
        $popup->update(['is_active' => !$popup->is_active]);
        return back()->with('success', 'Статус изменен!');
    }
}