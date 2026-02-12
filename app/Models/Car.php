<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    // Разрешаем заполнять эти поля массово
    protected $fillable = [
       'brand',
    'model',
    'image', // <--- Добавили это
    'year',
    'price',
    'mileage',
    'color',
    'description',
    'is_sold'
    ];


    // ... внутри класса Car

public function images()
{
    return $this->hasMany(CarImage::class);
}
}
