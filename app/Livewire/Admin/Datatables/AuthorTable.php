<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Author;

class AuthorTable extends DataTableComponent
{
    protected $model = Author::class;

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
            Column::make("Nacionalidad", "nacionalidad")
                ->sortable()
                ->searchable(),
            Column::make("Usuario", "user.name")
                ->label(function($row) {
                    return $row->user ? $row->user->name : '-';
                })
                ->searchable(),
            Column::make("Verificado", "is_verified")
                ->searchable()
                ->sortable()
                ->label(function($row) {
                    // Forzar recarga del modelo para asegurar que tenemos el valor más reciente
                    $row->refresh();
                    
                    // Leer el valor de todas las formas posibles
                    $value = $row->is_verified ?? $row->getAttribute('is_verified') ?? $row->getRawOriginal('is_verified') ?? false;
                    
                    // Convertir a boolean de forma explícita
                    $isVerified = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isVerified === null) {
                        $isVerified = ($value === 1 || $value === '1' || $value === true);
                    }
                    
                    if ($isVerified) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1"></i> Verificado
                                </span>';
                    } else {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <i class="fa-solid fa-circle-xmark mr-1"></i> No verificado
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
                    return view('admin.authors.actions', ['author' => $row]);
                })
        ];
    }
}

