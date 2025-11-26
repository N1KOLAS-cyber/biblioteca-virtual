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
        'name' => 'Editar',
    ],
]">
    <div class="p-6">
        {{-- Contenido de edición --}}
        <p class="font-bold text-gray-600"> </p>
    </div>
    <x-wire-card>
        <form action="{{route('admin.plans.update', $plan )}}" method="POST">
            @csrf
            @method('PUT')

            <x-wire-input label="Nombre" name="name" placeholder="Nombre del plan" value="{{old('name', $plan->name )}}">
            </x-wire-input>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Descripción del plan">{{old('description', $plan->description)}}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <x-wire-input label="Precio" name="price" type="number" step="0.01" min="0" placeholder="0.00" value="{{old('price', $plan->price )}}">
                </x-wire-input>
                <x-wire-input label="Duración (días)" name="duration_days" type="number" min="1" placeholder="Dejar vacío para permanente" value="{{old('duration_days', $plan->duration_days )}}">
                </x-wire-input>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <x-wire-input label="Días de prueba" name="trial_days" type="number" min="0" placeholder="0" value="{{old('trial_days', $plan->trial_days )}}">
                </x-wire-input>
                <x-wire-input label="Orden" name="order" type="number" min="0" placeholder="0" value="{{old('order', $plan->order )}}">
                </x-wire-input>
            </div>

            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Plan activo</span>
                </label>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>

