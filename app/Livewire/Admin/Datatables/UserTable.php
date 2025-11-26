<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;

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
                    return $row->roles->pluck('name')->join(', ') ?: 'Sin rol';
                }),
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

