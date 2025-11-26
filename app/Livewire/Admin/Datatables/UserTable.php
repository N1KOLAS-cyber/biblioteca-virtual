<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

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
            Column::make("Email", "email")
                ->sortable()
                ->searchable(),
            Column::make("Roles", "roles")
                ->label(function($row) {
                    if (!$row->relationLoaded('roles')) {
                        $row->load('roles');
                    }
                    return $row->roles->pluck('name')->join(', ') ?: 'Sin rol';
                }),
            Column::make("Plan", "plan")
                ->label(function($row) {
                    // Consultar directamente la tabla memberships para obtener el plan activo o en prueba
                    $membership = DB::table('memberships')
                        ->where('user_id', $row->id)
                        ->whereIn('status', ['active', 'trial'])
                        ->latest('started_at')
                        ->first();
                    
                    if ($membership) {
                        $plan = \App\Models\Plan::find($membership->plan_id);
                        if ($plan) {
                            $status = $membership->status;
                            
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'trial' => 'bg-blue-100 text-blue-800',
                                'expired' => 'bg-red-100 text-red-800',
                                'canceled' => 'bg-gray-100 text-gray-800',
                                'suspended' => 'bg-yellow-100 text-yellow-800',
                            ];
                            
                            $color = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                            
                            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">
                                        ' . $plan->name . '
                                    </span>';
                        }
                    }
                    
                    return '<span class="text-gray-500">Sin plan</span>';
                })
                ->html(),
            Column::make("Fecha de creación", "created_at")
                ->sortable()
                ->format(function($value) {
                    return $value ? $value->format('d/m/Y') : '-';
                }),
            Column::make("Acciones", "actions")
                ->label(function($row) {
                    return view('admin.users.actions', ['user' => $row]);
                })
        ];
    }
}

