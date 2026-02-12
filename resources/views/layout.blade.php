<!DOCTYPE html>
<html lang="ru" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Shop - @yield('title', 'Главная')</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Плавная смена темы */
        body { transition: background-color 0.3s, color 0.3s; }
        
        /* Стили карточек */
        .card { transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        
        /* Специфика темной темы */
        [data-bs-theme="dark"] .bg-light { background-color: #2b3035 !important; }
        [data-bs-theme="dark"] .card { border-color: #495057; }
        
        /* Ссылки без подчеркивания */
        a { text-decoration: none; }
        
        /* Прижимаем футер к низу */
        body { min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
    </style>
</head>
<body>

    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg border-bottom sticky-top bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🚗 Car Shop</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    <!-- Ссылка на каталог -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cars.index') ? 'active' : '' }}" href="{{ route('cars.index') }}">Каталог</a>
                    </li>

                    <!-- Кнопка смены темы (Луна/Солнце) -->
                    <li class="nav-item ms-2">
                        <button class="btn btn-outline-secondary rounded-circle border-0" id="themeToggle" title="Сменить тему">
                            <span id="themeIcon">🌙</span>
                        </button>
                    </li>

                    <!-- Блок для АДМИНИСТРАТОРА -->
                    @auth
                        <li class="nav-item ms-3">
                            <!-- Кнопка ведет в закрытую админку -->
                            <a class="btn btn-danger fw-bold shadow-sm" href="{{ route('admin.dashboard') }}">
                                ⚙ В Админку
                            </a>
                        </li>
                    @endauth

                    <!-- Блок для ГОСТЯ -->
                    @guest
                        <li class="nav-item ms-3">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4 shadow-sm">Войти</a>
                        </li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="py-4">
        <div class="container">
            
            <!-- Сообщения (Успех) -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Сообщения (Ошибки) -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
            
        </div>
    </main>

    <!-- Футер -->
    <footer class="py-3 bg-body-tertiary border-top mt-auto">
        <div class="container text-center">
            <span class="text-muted">
                &copy; {{ date('Y') }} <strong>Car Shop Project</strong>.
            </span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Скрипт переключения темы -->
    <script>
        const toggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        // Проверяем сохраненную тему
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-bs-theme', savedTheme);
        themeIcon.innerText = savedTheme === 'dark' ? '☀️' : '🌙';

        // Обработчик клика
        toggleBtn.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            themeIcon.innerText = newTheme === 'dark' ? '☀️' : '🌙';
            localStorage.setItem('theme', newTheme);
        });
    </script>
</body>
</html>