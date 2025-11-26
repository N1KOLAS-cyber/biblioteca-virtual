<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
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
        <form action="{{route('admin.users.update', $user )}}" method="POST">
            @csrf
            @method('PUT')

            <x-wire-input label="Nombre" name="name" placeholder="Nombre del usuario" value="{{old('name', $user->name )}}">
            </x-wire-input>
            <x-wire-input label="Email" name="email" type="email" placeholder="Email del usuario" value="{{old('email', $user->email )}}">
            </x-wire-input>
            <x-wire-input label="Nueva Contraseña" name="password" type="password" placeholder="Dejar en blanco para no cambiar">
            </x-wire-input>
            <x-wire-input label="Confirmar Nueva Contraseña" name="password_confirmation" type="password" placeholder="Confirmar nueva contraseña">
            </x-wire-input>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                <select name="role" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccionar rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role', $user->roles->first()?->id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Plan de Membresía (Opcional)</label>
                <select name="plan_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Sin plan</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $currentPlanId) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} - ${{ number_format($plan->price, 2, '.', ',') }} MXN
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

