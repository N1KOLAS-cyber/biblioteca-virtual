@php
    $user = auth()->user();
    $isStaff = $user->hasRole('staff') && !$user->hasRole('admin');
    
    // Arreglo de íconos con organización mejorada
    $links = [
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
    ];
    
    // Solo admin puede ver Contenido
    if (!$isStaff) {
        $links[] = ['header' => 'Contenido'];
        $links[] = [
            'name' => 'Autores',
            'icon' => 'fa-solid fa-pen-nib',
            'href' => route('admin.authors.index'),
            'active' => request()->routeIs('admin.authors.*'),
        ];
        $links[] = [
            'name' => 'Categorías',
            'icon' => 'fa-solid fa-tags',
            'href' => route('admin.categories.index'),
            'active' => request()->routeIs('admin.categories.*'),
            'submenu' => [
                [
                    'name' => 'Ver todas',
                    'href' => route('admin.categories.index'),
                    'active' => request()->routeIs('admin.categories.index'),
                ],
                [
                    'name' => 'Crear nueva',
                    'href' => route('admin.categories.create'),
                    'active' => request()->routeIs('admin.categories.create'),
                ],
            ],
        ];
        $links[] = [
            'name' => 'Libros',
            'icon' => 'fa-solid fa-book',
            'href' => route('admin.books.index'),
            'active' => request()->routeIs('admin.books.*'),
        ];
    }
    
    $links[] = ['header' => 'Usuarios y Permisos'];
    $links[] = [
        'name' => 'Usuarios',
        'icon' => 'fa-solid fa-users',
        'href' => route('admin.users.index'),
        'active' => request()->routeIs('admin.users.*'),
    ];
    
    // Solo admin puede ver Roles y permisos
    if (!$isStaff) {
        $links[] = [
            'name' => 'Roles y permisos',
            'icon' => 'fa-solid fa-shield-halved',
            'href' => route('admin.roles.index'),
            'active' => request()->routeIs('admin.roles.*'),
        ];
    }
    
    $links[] = ['header' => 'Configuración'];
    $links[] = [
        'name' => 'Planes',
        'icon' => 'fa-solid fa-layer-group',
        'href' => route('admin.plans.index'),
        'active' => request()->routeIs('admin.plans.*'),
    ];
@endphp

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full bg-gray-50 border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-gray-50">
        {{-- Logo NubeLectora --}}
        <div class="pt-4 pb-2 mb-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center ps-2.5">
                <img src="{{ asset('images/logo.png') }}" class="h-6 me-3" alt="NubeLectora Logo" />
                <span class="self-center text-xl font-semibold text-gray-900 whitespace-nowrap">NubeLectora</span>
            </a>
        </div>

        <ul class="space-y-1 font-medium">
            @foreach ($links as $link)
                <li>
                    {{-- Revisa si existe definido una llave llamada 'header' --}}
                    @isset($link['header'])
                        <div class="px-3 py-3 mt-4 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-t border-gray-200 first:border-t-0 first:mt-0">
                            {{$link['header']}}
                        </div>
                    @else
                        {{-- Revisa si tiene un submenu --}}
                        @isset($link['submenu'])
                            @php
                                $hasActiveSubmenu = collect($link['submenu'])->contains(function($item) {
                                    return $item['active'] ?? false;
                                });
                            @endphp
                            <button type="button" class="flex items-center justify-between w-full px-3 py-2.5 text-gray-700 transition duration-75 rounded-lg group hover:bg-gray-100 {{ $link['active'] || $hasActiveSubmenu ? 'bg-gray-200' : '' }}" aria-controls="dropdown-{{ $loop->index }}" data-collapse-toggle="dropdown-{{ $loop->index }}">
                                <div class="flex items-center">
                                    {{-- Ícono dinámico del arreglo --}}
                                    <span class="w-6 h-6 inline-flex justify-center items-center text-gray-600 {{ $link['active'] || $hasActiveSubmenu ? 'text-gray-900' : '' }}">
                                        <i class="{{ $link['icon'] }}"></i>
                                    </span>
                                    <span class="ms-3 text-left rtl:text-right whitespace-nowrap {{ $link['active'] || $hasActiveSubmenu ? 'font-medium' : '' }}">{{ $link['name'] }}</span>
                                </div>
                                {{-- Flecha para indicar que es un desplegable --}}
                                <svg class="w-3 h-3 text-gray-600 transition-transform duration-200 {{ $hasActiveSubmenu ? 'rotate-180' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            {{-- ID único para cada submenú para que el toggle funcione de forma independiente --}}
                            <ul id="dropdown-{{ $loop->index }}" class="{{ $hasActiveSubmenu ? '' : 'hidden' }} py-2 space-y-1 ml-4 border-l-2 border-gray-200">
                                @foreach ($link['submenu'] as $item)
                                    <li>
                                        <a href="{{ $item['href'] }}"
                                           class="flex items-center w-full px-3 py-2 text-sm text-gray-600 transition duration-75 rounded-lg pl-6 group hover:bg-gray-100 hover:text-gray-900 {{ $item['active'] ? 'bg-gray-100 text-gray-900 font-medium' : '' }}">
                                            <i class="fa-solid fa-circle text-xs mr-2 {{ $item['active'] ? 'text-blue-600' : 'text-gray-400' }}"></i>
                                            {{ $item['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{-- Elemento de menú simple, sin submenú --}}
                            <a href="{{ $link['href'] }}" class="flex items-center px-3 py-2.5 text-gray-700 rounded-lg hover:bg-gray-100 group transition-colors {{ $link['active'] ? 'bg-gray-200 text-gray-900 font-medium' : '' }}">
                                <span class="w-6 h-6 inline-flex justify-center items-center text-gray-600 {{ $link['active'] ? 'text-gray-900' : '' }}">
                                    <i class="{{ $link['icon'] }}"></i>
                                </span>
                                <span class="ms-3">{{ $link['name'] }}</span>
                            </a>
                        @endisset
                    @endisset
                </li>
            @endforeach
            </ul>
        </div>
        </aside>
