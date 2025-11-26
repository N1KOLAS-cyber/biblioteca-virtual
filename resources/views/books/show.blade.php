<x-app>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('catalog.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
            <i class="fa-solid fa-arrow-left mr-2"></i> Volver al catálogo
        </a>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $book->titulo }}</h1>
                    <p class="text-lg text-gray-600 mb-4">
                        <i class="fa-solid fa-pen-nib mr-2"></i> {{ $book->author->nombre ?? 'Sin autor' }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if($book->is_free)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fa-solid fa-gift mr-1"></i> Gratis
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fa-solid fa-lock mr-1"></i> Premium
                        </span>
                    @endif
                    
                    @auth
                        <button onclick="toggleFavorite({{ $book->id }})" 
                                class="p-2.5 rounded-lg {{ $isFavorite ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }} hover:bg-red-200 transition-colors focus:ring-4 focus:ring-red-300"
                                id="favorite-btn">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                    @endauth
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Páginas</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $book->paginas ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Idioma</p>
                    <p class="text-xl font-semibold text-gray-900">{{ strtoupper($book->idioma ?? 'N/A') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Año de publicación</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $book->año_publicacion ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-3">Sinopsis</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $book->sinopsis ?? 'Sin sinopsis disponible.' }}</p>
                </div>
            </div>

            <div class="flex gap-4">
                @if($canRead)
                    <a href="{{ route('books.read', $book->slug) }}" 
                       class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none inline-flex items-center">
                        <i class="fa-solid fa-book-open mr-2"></i> Leer libro
                    </a>
                @else
                    <button disabled
                            class="text-gray-400 bg-gray-200 cursor-not-allowed font-medium rounded-lg text-sm px-6 py-3 inline-flex items-center">
                        <i class="fa-solid fa-lock mr-2"></i> Requiere membresía
                    </button>
                @endif
            </div>
        </div>
    </div>

    @auth
    <script>
        function toggleFavorite(bookId) {
            fetch(`/libros/${bookId}/favorito`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById('favorite-btn');
                if (data.isFavorite) {
                    btn.classList.remove('bg-gray-100', 'text-gray-600');
                    btn.classList.add('bg-red-100', 'text-red-600');
                } else {
                    btn.classList.remove('bg-red-100', 'text-red-600');
                    btn.classList.add('bg-gray-100', 'text-gray-600');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    @endauth
</x-app>
