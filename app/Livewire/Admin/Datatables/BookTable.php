<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Book;

class BookTable extends DataTableComponent
{
    protected $model = Book::class;

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
            Column::make("Título", "titulo")
                ->sortable()
                ->searchable(),
            Column::make("Autor", "author.nombre")
                ->label(function($row) {
                    // Cargar la relación si no está cargada
                    if (!$row->relationLoaded('author')) {
                        $row->load('author');
                    }
                    
                    return $row->author ? $row->author->nombre : '-';
                })
                ->searchable(),
            Column::make("Tipo", "is_free")
                ->searchable()
                ->sortable()
                ->label(function($row) {
                    // Usar el cast del modelo para obtener el valor booleano correcto
                    $isFree = (bool) $row->is_free;
                    
                    if ($isFree) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-gift mr-1"></i> Gratis
                                </span>';
                    } else {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <i class="fa-solid fa-lock mr-1"></i> Premium
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
                    return view('admin.books.actions', ['book' => $row]);
                })
        ];
    }
}
