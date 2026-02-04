<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::create('cars', function (Blueprint $table) {
        $table->id();
        $table->string('brand');        // Марка (Toyota, BMW)
        $table->string('model');        // Модель (Camry, X5)
        $table->integer('year');        // Год выпуска
        $table->decimal('price', 10, 2); // Цена
        $table->integer('mileage')->nullable(); // Пробег (может быть пустым, если новая)
        $table->string('color')->nullable();    // Цвет
        $table->text('description')->nullable(); // Описание
        $table->boolean('is_sold')->default(false); // Продана или нет
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
