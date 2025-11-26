<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'href' => route('admin.categories.index'),
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
        <form action="{{route('admin.categories.update', $category )}}" method="POST">
            @csrf
            @method('PUT')

            <x-wire-input label="Nombre" name="nombre" placeholder="Nombre de la categoría" value="{{old('nombre', $category->nombre )}}">
            </x-wire-input>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Descripción de la categoría">{{old('descripcion', $category->descripcion)}}</textarea>
            </div>
            
            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Categoría activa (se mostrará en el catálogo)</span>
                </label>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

