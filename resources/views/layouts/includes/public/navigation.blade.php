<nav class="bg-white border-b border-gray-200 px-4 py-3">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" class="h-6" alt="NubeLectora Logo" />
                <span class="text-xl font-semibold text-gray-900 whitespace-nowrap">NubeLectora</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="{{ route('home') }}" 
               class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : '' }}">
                <i class="fa-solid fa-home mr-1"></i> Inicio
            </a>
            <a href="{{ route('catalog.index') }}" 
               class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('catalog.*') ? 'text-blue-600 font-semibold' : '' }}">
                <i class="fa-solid fa-book-open mr-1"></i> Catálogo
            </a>
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-gauge mr-1"></i> Dashboard
                </a>
            @endauth
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-4">
            @auth
                <!-- User Dropdown -->
                <div class="relative">
                    <button id="user-menu-button" type="button" 
                            class="flex items-center space-x-2 text-sm rounded-lg focus:ring-4 focus:ring-gray-200 px-2 py-1 hover:bg-gray-100 transition-colors">
                        <span class="sr-only">Abrir menú de usuario</span>
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden md:block text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="user-menu" class="hidden absolute right-0 top-full mt-2 w-56 z-50 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg border border-gray-200">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900 font-semibold">{{ auth()->user()->name }}</span>
                            <span class="block text-sm text-gray-500 truncate">{{ auth()->user()->email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-user mr-2"></i> Perfil
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" 
                   class="text-gray-700 hover:text-blue-600 px-3 py-2">
                    Iniciar sesión
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Registrarse
                    </a>
                @endif
            @endauth
        </div>

        <!-- Mobile menu button -->
        <button id="mobile-menu-button" type="button" 
                class="md:hidden inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
            <span class="sr-only">Abrir menú principal</span>
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200 mt-3">
            <a href="{{ route('home') }}" 
               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg">
                <i class="fa-solid fa-home mr-2"></i> Inicio
            </a>
            <a href="{{ route('catalog.index') }}" 
               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg">
                <i class="fa-solid fa-book-open mr-2"></i> Catálogo
            </a>
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg">
                    <i class="fa-solid fa-gauge mr-2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg">
                    Iniciar sesión
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" 
                       class="block px-3 py-2 text-base font-medium text-blue-600 hover:bg-blue-50 rounded-lg">
                        Registrarse
                    </a>
                @endif
            @endauth
        </div>
    </div>
</nav>

<script>
    // Toggle mobile menu
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Toggle user dropdown
    document.getElementById('user-menu-button')?.addEventListener('click', function() {
        const menu = document.getElementById('user-menu');
        menu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const userButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        if (userButton && userMenu && !userButton.contains(event.target) && !userMenu.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });
</script>

