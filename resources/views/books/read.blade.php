<x-app>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('books.show', $book->slug) }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fa-solid fa-arrow-left mr-2"></i> Volver al libro
            </a>
            <div class="flex items-center gap-2">
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
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $book->titulo }}</h1>
            <p class="text-lg text-gray-600 mb-8">
                <i class="fa-solid fa-pen-nib mr-2"></i> {{ $book->author->nombre ?? 'Sin autor' }}
            </p>

            <div class="prose max-w-none">
                <div class="text-gray-700 leading-relaxed whitespace-pre-wrap text-base">
                    {{ $book->sinopsis ?? 'Contenido no disponible.' }}
                </div>
            </div>
        </div>
    </div>
</x-app>
