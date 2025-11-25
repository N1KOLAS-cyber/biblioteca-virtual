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
        'name' => 'Editar',
    ],
]">
    <div class="p-6">
        {{-- Contenido de edición --}}
        <p class="font-bold text-gray-600"> </p>
    </div>
</x-admin-layout>
