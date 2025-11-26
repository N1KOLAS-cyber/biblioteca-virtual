<x-app>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header compacto -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Bienvenido, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-600">Explora nuestra colección de libros</p>
        </div>

        <!-- Libros destacados - Ocupa todo el ancho arriba -->
        @if($featuredBooks->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fa-solid fa-star mr-2 text-yellow-500"></i> Libros Destacados
                    </h2>
                    <a href="{{ route('catalog.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                        Ver todos <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredBooks->take(6) as $book)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md transition-shadow p-5 flex flex-col h-full">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1 mr-2">{{ $book->titulo }}</h3>
                                @if($book->is_free)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shrink-0">
                                        <i class="fa-solid fa-gift mr-1"></i> Gratis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 shrink-0">
                                        <i class="fa-solid fa-lock mr-1"></i> Premium
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-3">
                                <i class="fa-solid fa-pen-nib mr-1.5"></i> {{ $book->author->nombre ?? 'Sin autor' }}
                            </p>
                            
                            <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-grow">{{ \Illuminate\Support\Str::limit($book->sinopsis, 120) }}</p>
                            
                            <a href="{{ route('books.show', $book->slug) }}" 
                               class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center focus:outline-none block mt-auto">
                                Ver detalles
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Sección inferior: Categorías, Autores y Acciones Rápidas en fila -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Categorías populares -->
            @if($categories->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">
                            <i class="fa-solid fa-tags mr-2 text-blue-600"></i> Categorías
                        </h2>
                        <a href="{{ route('catalog.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Ver todas
                        </a>
                    </div>
                    <div class="space-y-2">
                        @foreach($categories->take(5) as $category)
                            <a href="{{ route('catalog.index', ['category_id' => $category->id]) }}" 
                               class="flex items-center p-2.5 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fa-solid fa-tag text-blue-600 mr-2 text-sm"></i>
                                <span class="text-sm text-gray-700 font-medium flex-1">{{ $category->nombre }}</span>
                                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Autores destacados -->
            @if($authors->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">
                            <i class="fa-solid fa-pen-nib mr-2 text-green-600"></i> Autores
                        </h2>
                        <a href="{{ route('catalog.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Ver todos
                        </a>
                    </div>
                    <div class="space-y-2">
                        @foreach($authors->take(5) as $author)
                            <a href="{{ route('catalog.index', ['author_id' => $author->id]) }}" 
                               class="flex items-center p-2.5 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2 shrink-0">
                                    <i class="fa-solid fa-user text-green-600 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $author->nombre }}</p>
                                    @if($author->is_verified)
                                        <p class="text-xs text-green-600">
                                            <i class="fa-solid fa-check-circle mr-1"></i> Verificado
                                        </p>
                                    @endif
                                </div>
                                <i class="fa-solid fa-chevron-right text-gray-400 text-xs ml-2 shrink-0"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Acciones rápidas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Acciones Rápidas</h2>
                <div class="space-y-3">
                    <a href="{{ route('catalog.index') }}" 
                       class="flex items-center p-3 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-lg transition-all shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-book-open text-2xl mr-3 text-blue-600"></i>
                        <p class="font-semibold text-gray-900">Explorar Catálogo</p>
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center p-3 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-lg transition-all shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-gauge text-2xl mr-3 text-blue-600"></i>
                        <p class="font-semibold text-gray-900">Mi Dashboard</p>
                    </a>
                    <a href="{{ route('catalog.index', ['search' => '']) }}" 
                       class="flex items-center p-3 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-lg transition-all shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-search text-2xl mr-3 text-blue-600"></i>
                        <p class="font-semibold text-gray-900">Buscar Libros</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app>

