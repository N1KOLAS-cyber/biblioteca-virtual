<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
    ],
]">
    <div class="p-6">
        {{-- Tarjetas de estadísticas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total de Usuarios --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Usuarios</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['users'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-4">
                        <i class="fa-solid fa-users text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total de Libros --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Libros</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['books'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-lg p-4">
                        <i class="fa-solid fa-book text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total de Autores --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Autores</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['authors'] }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-lg p-4">
                        <i class="fa-solid fa-pen-nib text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total de Categorías --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Categorías Activas</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['categories'] }}</p>
                    </div>
                    <div class="bg-orange-100 rounded-lg p-4">
                        <i class="fa-solid fa-tags text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actividad Reciente --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Actividad Reciente</h2>
            
            @if($recentActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                        <div class="flex items-start space-x-4 pb-4 border-b border-gray-200 last:border-0">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $activity['bg_color'] }}">
                                    <i class="{{ $activity['icon'] }} {{ $activity['icon_color'] }}"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800">{{ $activity['message'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $activity['details'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @php
                                        $diff = $activity['time']->diffForHumans();
                                    @endphp
                                    {{ $diff }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No hay actividad reciente</p>
            @endif
        </div>
    </div>
</x-admin-layout>
