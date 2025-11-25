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
</x-admin-layout>
