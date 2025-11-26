<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
    ],
]">
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Panel de Staff</h1>
            <p class="text-gray-600 mt-1">Gestión de usuarios y membresías</p>
        </div>

        {{-- Tarjetas de estadísticas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Usuarios Activos --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Usuarios Activos</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_users'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-4">
                        <i class="fa-solid fa-users text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Membresías Activas --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Membresías Activas</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_memberships'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-lg p-4">
                        <i class="fa-solid fa-layer-group text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Membresías por Vencer --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Por Vencer (7 días)</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['expiring_memberships'] }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-lg p-4">
                        <i class="fa-solid fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Membresías Vencidas --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Membresías Vencidas</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['expired_memberships'] }}</p>
                    </div>
                    <div class="bg-red-100 rounded-lg p-4">
                        <i class="fa-solid fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Membresías por Vencer --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Membresías por Vencer</h2>
                    @if($stats['expiring_memberships'] > 0)
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                            {{ $stats['expiring_memberships'] }} {{ $stats['expiring_memberships'] == 1 ? 'membresía' : 'membresías' }}
                        </span>
                    @endif
                </div>
                
                @if($expiringMembershipsList->count() > 0)
                    <div class="space-y-4">
                        @foreach($expiringMembershipsList as $membership)
                            <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $membership->name }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $membership->email }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fa-solid fa-layer-group mr-1"></i> {{ $membership->plan_name }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-yellow-600">
                                            {{ \Carbon\Carbon::parse($membership->expires_at)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($membership->expires_at)->diffForHumans() }}
                                        </p>
                                        @if($membership->status === 'trial')
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                Prueba
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                            Ver todos los usuarios <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fa-solid fa-check-circle text-6xl text-green-300 mb-4"></i>
                        <p class="text-gray-600">No hay membresías por vencer en los próximos 7 días</p>
                    </div>
                @endif
            </div>

            {{-- Usuarios Recientes --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Usuarios Recientes</h2>
                </div>
                
                @if($recentUsers->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                            <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $user->email }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Registrado {{ $user->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div>
                                        @php
                                            $userMembership = \Illuminate\Support\Facades\DB::table('memberships')
                                                ->where('user_id', $user->id)
                                                ->whereIn('status', ['active', 'trial'])
                                                ->latest('started_at')
                                                ->first();
                                        @endphp
                                        @if($userMembership)
                                            @php
                                                $userPlan = \App\Models\Plan::find($userMembership->plan_id);
                                            @endphp
                                            @if($userPlan)
                                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                    {{ $userPlan->name }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded">
                                                Sin plan
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                            Ver todos los usuarios <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fa-solid fa-users text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">No hay usuarios recientes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

