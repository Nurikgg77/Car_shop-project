<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Shop - @yield('title', 'Главная')</title>
    
    <!-- Подключаем Bootstrap 5 (CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Делаем фон чуть сероватым для контраста с белыми карточками */
        body { 
            background-color: #f8f9fa; 
        }
        
        /* Стили для карточек */
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top { 
            height: 220px; 
            object-fit: cover; 
            background-color: #eee; 
        }
        .price-tag { 
            font-size: 1.25rem; 
            font-weight: bold; 
            color: #198754; 
        }
        
        /* Убираем подчеркивание у ссылок в карточках */
        a { text-decoration: none; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100"> <!-- Flex классы, чтобы футер был внизу -->

    <!-- Навигационная панель (Меню) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🚗 Car Shop</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cars.index') ? 'active' : '' }}" href="{{ route('cars.index') }}">
                            Каталог
                        </a>
                    </li>

                    <!-- Блок для АДМИНИСТРАТОРА -->
                    @auth
                        <li class="nav-item">
                            <a class="btn btn-success ms-lg-3 btn-sm" href="{{ route('cars.create') }}">
                                + Продать авто
                            </a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <span class="navbar-text text-white small me-2">
                                Привет, {{ Auth::user()->name }}
                            </span>
                            <!-- Форма выхода (POST запрос) -->
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm text-light border-secondary">
                                    Выйти
                                </button>
                            </form>
                        </li>
                    @endauth

                    <!-- Блок для ГОСТЕЙ -->
                    @guest
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                                Войти
                            </a>
                        </li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="flex-shrink-0 py-4">
        <div class="container">
            
            <!-- Вывод сообщений об успехе (зеленые) -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Вывод сообщений об ошибках (красные) -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
            
        </div>
    </main>

    <!-- Футер -->
    <footer class="footer mt-auto py-3 bg-white border-top">
        <div class="container text-center">
            <span class="text-muted">
                &copy; {{ date('Y') }} <strong>Car Shop Project</strong>. Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </span>
        </div>
    </footer>

    <!-- Подключаем Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>