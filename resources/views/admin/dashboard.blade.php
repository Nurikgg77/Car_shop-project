@extends('admin_layout')

@section('content')
    <h2 class="mb-4">Добро пожаловать, {{ Auth::user()->name }}!</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card bg-primary text-white border-0 p-3">
                <h3>{{ $totalCars }}</h3>
                <span>Всего машин</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white border-0 p-3">
                <h3>{{ $soldCars }}</h3>
                <span>Продано</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark border-0 p-3">
                <h3>{{ $totalBanners }}</h3>
                <span>Активных баннеров</span>
            </div>
        </div>
    </div>

    <h4>Последние поступления</h4>
    <table class="table table-dark table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Модель</th>
                <th>Цена</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody>
            @foreach($latestCars as $car)
                <tr>
                    <td>#{{ $car->id }}</td>
                    <td>{{ $car->brand }} {{ $car->model }}</td>
                    <td>${{ number_format($car->price) }}</td>
                    <td>{{ $car->created_at->format('d.m.Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection