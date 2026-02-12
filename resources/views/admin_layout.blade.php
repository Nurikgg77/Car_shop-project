<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark"> <!-- Админка сразу темная для солидности -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Car Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; }
        .sidebar { min-width: 250px; background-color: #212529; border-right: 1px solid #373b3e; min-height: 100vh; }
        .sidebar a { color: #adb5bd; padding: 10px 20px; display: block; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background-color: #0d6efd; color: white; }
        .content { flex: 1; padding: 20px; background-color: #2b3035; color: white; }
    </style>
</head>
<body>

    <!-- БОКОВОЕ МЕНЮ -->
    <div class="sidebar d-flex flex-column p-3">
        <h3 class="text-white mb-4 ps-2">🛠 Admin Panel</h3>
        
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            📊 Дашборд
        </a>
        <a href="{{ route('admin.cars') }}" class="{{ request()->routeIs('admin.cars') ? 'active' : '' }}">
            🚗 Автомобили
        </a>
        <a href="{{ route('banners.index') }}" class="{{ request()->routeIs('banners.index') ? 'active' : '' }}">
            📢 Баннеры
        </a>
        
        <hr class="text-secondary">
        
        <a href="{{ route('home') }}" target="_blank">🌐 Перейти на сайт</a>
        
        <form action="{{ route('logout') }}" method="POST" class="mt-auto">
            @csrf
            <button class="btn btn-outline-danger w-100 mt-3">Выйти</button>
        </form>
    </div>

    <!-- ОСНОВНОЙ КОНТЕНТ -->
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @yield('content')
    </div>

</body>
</html>