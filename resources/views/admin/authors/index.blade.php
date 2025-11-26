<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Autores',
    ],
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.authors.create') }}">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo
        </x-wire-button>
    </x-slot>
    <div class="p-6">
        {{-- Contenido de la página --}}
    </div>

    @livewire('admin.datatables.author-table')
</x-admin-layout>

