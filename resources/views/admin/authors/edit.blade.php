<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Autores',
        'href' => route('admin.authors.index'),
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
        <form action="{{route('admin.authors.update', $author )}}" method="POST">
            @csrf
            @method('PUT')

            <x-wire-input label="Nombre" name="nombre" placeholder="Nombre del autor" value="{{old('nombre', $author->nombre )}}">
            </x-wire-input>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Biografía</label>
                <textarea name="biografia" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Biografía del autor">{{old('biografia', $author->biografia)}}</textarea>
            </div>
            <x-wire-input label="Foto (URL)" name="foto" type="text" placeholder="URL de la foto" value="{{old('foto', $author->foto )}}">
            </x-wire-input>
            <x-wire-input label="Fecha de nacimiento" name="fecha_nacimiento" type="date" value="{{old('fecha_nacimiento', $author->fecha_nacimiento?->format('Y-m-d'))}}">
            </x-wire-input>
            <x-wire-input label="Nacionalidad" name="nacionalidad" placeholder="Nacionalidad" value="{{old('nacionalidad', $author->nacionalidad )}}">
            </x-wire-input>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Usuario (si es escritor del sistema)</label>
                <select name="user_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccionar usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $author->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', $author->is_verified) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Autor verificado (Admin acepta/verifica al autor)</span>
                </label>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

