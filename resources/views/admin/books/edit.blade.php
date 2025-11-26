<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Libros',
        'href' => route('admin.books.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">
    <div class="p-6">
        {{-- Contenido de edición --}}
        <p class="font-bold text-gray-600"> </p>
    </div>
    <x-wire-card>
        <form action="{{route('admin.books.update', $book )}}" method="POST">
            @csrf
            @method('PUT')

            <x-wire-input label="Título" name="titulo" placeholder="Título del libro" value="{{old('titulo', $book->titulo )}}">
            </x-wire-input>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sinopsis (Contenido del libro)</label>
                <textarea name="sinopsis" rows="10" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contenido completo del libro">{{old('sinopsis', $book->sinopsis)}}</textarea>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Autor</label>
                <select name="author_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Seleccionar autor</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                            {{ $author->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <x-wire-input label="Páginas" name="paginas" type="number" placeholder="Número de páginas" value="{{old('paginas', $book->paginas )}}">
                </x-wire-input>
                <x-wire-input label="Idioma" name="idioma" placeholder="Idioma (ej: es, en)" value="{{old('idioma', $book->idioma )}}">
                </x-wire-input>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <x-wire-input label="Año de publicación" name="año_publicacion" type="number" placeholder="Año" value="{{old('año_publicacion', $book->año_publicacion )}}">
                </x-wire-input>
                <x-wire-input label="Editorial" name="editorial" placeholder="Editorial" value="{{old('editorial', $book->editorial )}}">
                </x-wire-input>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Categorías</label>
                <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @foreach($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', $book->categories->pluck('id')->toArray())) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $category->nombre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_free" value="1" {{ old('is_free', $book->is_free) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Libro gratuito</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Libro destacado</span>
                </label>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

