<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Category;

class CategoryTable extends DataTableComponent
{
    protected $model = Category::class;

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
            Column::make("Nombre", "nombre")
                ->sortable()
                ->searchable(),
            Column::make("Activa", "is_active")
                ->searchable()
                ->sortable()
                ->label(function($row) {
                    // Forzar recarga del modelo para asegurar que tenemos el valor más reciente
                    $row->refresh();
                    
                    // Leer el valor de todas las formas posibles
                    $value = $row->is_active ?? $row->getAttribute('is_active') ?? $row->getRawOriginal('is_active') ?? false;
                    
                    // Convertir a boolean de forma explícita
                    $isActive = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isActive === null) {
                        $isActive = ($value === 1 || $value === '1' || $value === true);
                    }
                    
                    if ($isActive) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1"></i> Activa
                                </span>';
                    } else {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> Inactiva
                                </span>';
                    }
                })
                ->html(),
            Column::make("Libros", "books_count")
                ->sortable(),
            Column::make("Fecha de creación", "created_at")
                ->sortable()
                ->format(function($value) {
                    return $value ? $value->format('d/m/Y') : '-';
                }),
            Column::make("Acciones", "actions")
                ->label(function($row) {
                    return view('admin.categories.actions', ['category' => $row]);
                })
        ];
    }
}

