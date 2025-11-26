<x-app>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 id="typing-title" class="text-5xl md:text-6xl font-bold mb-6 min-h-[4rem] md:min-h-[5rem]">
                    <span id="typing-text"></span><span id="typing-cursor" class="animate-pulse">|</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto">
                    Descubre miles de libros y sumérgete en historias fascinantes. Tu biblioteca digital está a un clic de distancia.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('catalog.index') }}" 
                       class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-3.5 focus:outline-none inline-flex items-center justify-center">
                        <i class="fa-solid fa-book-open mr-2"></i> Explorar Catálogo
                    </a>
                    @guest
                        <a href="{{ route('register') }}" 
                           class="text-blue-600 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-lg px-8 py-3.5 focus:outline-none inline-flex items-center justify-center">
                            <i class="fa-solid fa-user-plus mr-2"></i> Crear Cuenta
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                ¿Por qué elegirnos?
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Ofrecemos una experiencia de lectura única con acceso a miles de libros digitales
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-book text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Amplia Colección</h3>
                <p class="text-gray-600">
                    Accede a miles de libros de diferentes géneros y autores reconocidos
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-mobile-screen-button text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Lee en Cualquier Lugar</h3>
                <p class="text-gray-600">
                    Accede a tus libros favoritos desde cualquier dispositivo, en cualquier momento
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-heart text-3xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Personaliza tu Experiencia</h3>
                <p class="text-gray-600">
                    Guarda tus libros favoritos y mantén un historial de lectura personalizado
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    @guest
    <div class="bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    ¿Listo para comenzar?
                </h2>
                <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                    Únete a nuestra comunidad de lectores y comienza a explorar un mundo de historias increíbles
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-3.5 focus:outline-none inline-flex items-center justify-center">
                        <i class="fa-solid fa-user-plus mr-2"></i> Registrarse Gratis
                    </a>
                    <a href="{{ route('login') }}" 
                       class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-lg px-8 py-3.5 focus:outline-none inline-flex items-center justify-center">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Stats Section -->
    <div class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">1000+</div>
                    <div class="text-gray-600">Libros Disponibles</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">500+</div>
                    <div class="text-gray-600">Autores</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">50+</div>
                    <div class="text-gray-600">Categorías</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">24/7</div>
                    <div class="text-gray-600">Disponible</div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const text = 'Bienvenido a Biblioteca Virtual';
            const typingElement = document.getElementById('typing-text');
            const cursorElement = document.getElementById('typing-cursor');
            let index = 0;
            
            function typeWriter() {
                if (index < text.length) {
                    typingElement.textContent += text.charAt(index);
                    index++;
                    setTimeout(typeWriter, 100); // Velocidad de escritura (100ms por letra)
                } else {
                    // Una vez terminado, ocultar el cursor después de un breve delay
                    setTimeout(function() {
                        cursorElement.style.display = 'none';
                    }, 500);
                }
            }
            
            // Iniciar el efecto después de un pequeño delay
            setTimeout(typeWriter, 500);
        });
    </script>
    <style>
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
    </style>
    @endpush
</x-app>

