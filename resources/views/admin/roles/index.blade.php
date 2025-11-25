<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Roles',
    ],
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.roles.create') }}">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo
        </x-wire-button>
    </x-slot>
    <div class="p-6">
        {{-- Contenido de la página --}}
    </div>

    @livewire('admin.datatables.role-table')
</x-admin-layout>
