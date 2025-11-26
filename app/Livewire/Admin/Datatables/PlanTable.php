<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PlanTable extends DataTableComponent
{
    protected $model = Plan::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchEnabled();
        $this->setDefaultSort('id', 'asc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->searchable(),
            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),
            Column::make("Precio", "price")
                ->sortable()
                ->format(function($value) {
                    return '$' . number_format($value, 2, '.', ',') . ' MXN';
                }),
            Column::make("Duración", "duration_days")
                ->sortable()
                ->label(function($row) {
                    if ($row->duration_days === null) {
                        return '<span class="text-gray-500">Permanente</span>';
                    }
                    return $row->duration_days . ' días';
                })
                ->html(),
            Column::make("Estado", "is_active")
                ->sortable()
                ->searchable()
                ->label(function($row) {
                    // Verificar si el plan tiene usuarios activos
                    $hasActiveUsers = \DB::table('memberships')
                        ->where('plan_id', $row->id)
                        ->whereIn('status', ['active', 'trial'])
                        ->exists();
                    
                    // El plan está activo si is_active es true O si tiene usuarios activos
                    $isActive = (bool) $row->is_active || $hasActiveUsers;
                    
                    if ($isActive) {
                        $badgeText = $hasActiveUsers ? 'En uso' : 'Activo';
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1"></i> ' . $badgeText . '
                                </span>';
                    } else {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> Inactivo
                                </span>';
                    }
                })
                ->html(),
            Column::make("Fecha de creación", "created_at")
                ->sortable()
                ->format(function($value) {
                    return $value ? $value->format('d/m/Y') : '-';
                }),
            Column::make("Acciones", "actions")
                ->label(function($row) {
                    return view('admin.plans.actions', ['plan' => $row]);
                })
                ->unclickable()
        ];
    }
}
