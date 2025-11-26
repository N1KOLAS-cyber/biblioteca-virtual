<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Planes',
        'href' => route('admin.plans.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">
    <div class="p-6">
        {{-- Contenido de creación --}}
    </div>

    <x-wire-card>
        <form action="{{route('admin.plans.store')}}" method="POST">
            @csrf
            <x-wire-input label="Nombre" name="name" placeholder="Nombre del plan" value="{{old('name')}}">
            </x-wire-input>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Descripción del plan">{{old('description')}}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <x-wire-input label="Precio" name="price" type="number" step="0.01" min="0" placeholder="0.00" value="{{old('price')}}">
                </x-wire-input>
                <x-wire-input label="Duración (días)" name="duration_days" type="number" min="1" placeholder="Dejar vacío para permanente" value="{{old('duration_days')}}">
                </x-wire-input>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <x-wire-input label="Días de prueba" name="trial_days" type="number" min="0" placeholder="0" value="{{old('trial_days', 0)}}">
                </x-wire-input>
                <x-wire-input label="Orden" name="order" type="number" min="0" placeholder="0" value="{{old('order', 0)}}">
                </x-wire-input>
            </div>

            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Plan activo</span>
                </label>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Guardar</x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>

