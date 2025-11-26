<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Roles y permisos',
        'href' => route('admin.roles.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">
    <div class="p-6">
        {{-- Contenido de creación --}}
    </div>

    <x-wire-card>
        <form action="{{route('admin.roles.store')}}" method="POST">
            @csrf
            <x-wire-input label="Nombre" name="name" placeholder="Nombre del rol" value="{{old('name')}}">
            </x-wire-input>
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Guardar</x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
