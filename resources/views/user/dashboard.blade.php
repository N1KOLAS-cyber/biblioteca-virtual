<x-app>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Mi Dashboard</h1>
            <p class="text-lg text-gray-600">Bienvenido, {{ auth()->user()->name }}</p>
        </div>

        <!-- Plan actual -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                <i class="fa-solid fa-layer-group mr-2 text-blue-600"></i> Plan Actual
            </h2>
            @if($currentPlan)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $currentPlan->name }}</p>
                        <p class="text-gray-600 mt-2">{{ $currentPlan->description ?? 'Sin descripción' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-blue-600">${{ number_format($currentPlan->price, 2) }} MXN</p>
                        @if($currentPlan->duration_days)
                            <p class="text-sm text-gray-500 mt-1">{{ $currentPlan->duration_days }} días</p>
                        @else
                            <p class="text-sm text-gray-500 mt-1">Permanente</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fa-solid fa-layer-group text-6xl text-gray-400 mb-4"></i>
                    <p class="text-xl font-semibold text-gray-900 mb-2">Sin plan activo</p>
                    <p class="text-gray-600">Explora nuestros planes disponibles</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Libros favoritos -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <i class="fa-solid fa-heart mr-2 text-red-500"></i> Libros Favoritos
                </h2>
                @if($favorites->count() > 0)
                    <div class="space-y-4">
                        @foreach($favorites as $favorite)
                            <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $favorite->titulo }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fa-solid fa-pen-nib mr-1"></i> {{ $favorite->author_name ?? 'Sin autor' }}
                                </p>
                                <a href="{{ route('books.show', $favorite->slug) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                                    Ver libro <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('catalog.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                            Ver todos los favoritos <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fa-solid fa-heart text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 mb-4">Aún no tienes libros favoritos</p>
                        <a href="{{ route('catalog.index') }}" 
                           class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none inline-block">
                            Explorar catálogo
                        </a>
                    </div>
                @endif
            </div>

            <!-- Historial de lectura -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <i class="fa-solid fa-clock-rotate-left mr-2 text-blue-500"></i> Historial de Lectura
                </h2>
                @if($readingHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($readingHistory as $history)
                            <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $history->titulo }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fa-solid fa-pen-nib mr-1"></i> {{ $history->author_name ?? 'Sin autor' }}
                                </p>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        @if($history->progress)
                                            <div class="w-32 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $history->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 font-medium">{{ $history->progress }}%</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('books.read', $history->slug) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                                        Continuar <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                                @if($history->last_read_at)
                                    <p class="text-xs text-gray-500">
                                        Última lectura: {{ \Carbon\Carbon::parse($history->last_read_at)->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fa-solid fa-book-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 mb-4">Aún no has leído ningún libro</p>
                        <a href="{{ route('catalog.index') }}" 
                           class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none inline-block">
                            Explorar catálogo
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app>
