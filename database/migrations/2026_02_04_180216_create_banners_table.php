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
    Schema::create('banners', function (Blueprint $table) {
        $table->id();
        $table->string('image');        // Путь к картинке
        $table->string('title')->nullable(); // Заголовок на баннере
        $table->text('text')->nullable();    // Описание
        $table->boolean('is_active')->default(true); // Показывать или нет
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
