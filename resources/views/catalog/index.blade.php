<x-app>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Catálogo de Libros</h1>
            <p class="text-lg text-gray-600">Explora nuestra colección de libros</p>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('catalog.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Búsqueda -->
                    <div>
                        <label for="search" class="block mb-2 text-sm font-medium text-gray-900">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                   placeholder="Título, autor, sinopsis..." 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                        </div>
                    </div>

                    <!-- Filtro por autor -->
                    <div>
                        <label for="author_id" class="block mb-2 text-sm font-medium text-gray-900">Autor</label>
                        <select id="author_id" name="author_id" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Todos los autores</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por categoría -->
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Categoría</label>
                        <select id="category_id" name="category_id" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        <i class="fa-solid fa-search mr-2"></i> Buscar
                    </button>
                    <a href="{{ route('catalog.index') }}" 
                       class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        <i class="fa-solid fa-xmark mr-2"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Grid de libros -->
        @if($books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($books as $book)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1 mr-2">{{ $book->titulo }}</h3>
                                @if($book->is_free)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fa-solid fa-gift mr-1"></i> Gratis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fa-solid fa-lock mr-1"></i> Premium
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fa-solid fa-pen-nib mr-1"></i> {{ $book->author->nombre ?? 'Sin autor' }}
                            </p>
                            
                            <p class="text-sm text-gray-500 line-clamp-3 mb-4">{{ \Illuminate\Support\Str::limit($book->sinopsis, 100) }}</p>
                            
                            <a href="{{ route('books.show', $book->slug) }}" 
                               class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center focus:outline-none block">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{ $books->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <i class="fa-solid fa-book-open text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No se encontraron libros</h3>
                <p class="text-gray-600">Intenta ajustar tus filtros de búsqueda</p>
            </div>
        @endif
    </div>
</x-app>
