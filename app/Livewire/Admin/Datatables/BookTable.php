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
            Column::make("Autor", "author_id")
                ->sortable()
                ->searchable()
                ->label(function($row) {
                    // Obtener el ID del libro primero
                    $bookId = $row->id ?? $row->getAttribute('id') ?? null;
                    
                    if ($bookId) {
                        // Consultar directamente el libro desde la base de datos
                        $book = Book::find($bookId);
                        if ($book && $book->author_id) {
                            $author = \App\Models\Author::find($book->author_id);
                            if ($author) {
                                return $author->nombre;
                            }
                        }
                    }
                    
                    // Fallback: intentar leer del row directamente
                    $authorId = $row->author_id ?? $row->getAttribute('author_id') ?? null;
                    
                    if (!$authorId) {
                        return '<span class="text-gray-400">Sin autor</span>';
                    }
                    
                    $author = \App\Models\Author::find($authorId);
                    if ($author) {
                        return $author->nombre;
                    }
                    
                    return '<span class="text-gray-400">Sin autor</span>';
                })
                ->html(),
            Column::make("Tipo", "is_free")
                ->searchable()
                ->sortable()
                ->label(function($row) {
                    // Obtener el ID del libro
                    $bookId = $row->id ?? $row->getAttribute('id') ?? null;
                    
                    // Consultar directamente desde la base de datos
                    if ($bookId) {
                        $book = Book::find($bookId);
                        if ($book) {
                            $isFree = (bool) $book->is_free;
                            
                            if ($isFree) {
                                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fa-solid fa-gift mr-1"></i> Gratis
                                        </span>';
                            } else {
                                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <i class="fa-solid fa-lock mr-1"></i> Premium
                                        </span>';
                            }
                        }
                    }
                    
                    // Fallback: intentar leer del row directamente
                    $value = $row->is_free ?? $row->getAttribute('is_free') ?? $row->getRawOriginal('is_free') ?? false;
                    $isFree = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isFree === null) {
                        $isFree = ($value === 1 || $value === '1' || $value === true);
                    }
                    
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
            Column::make("Categorías", "categories")
                ->label(function($row) {
                    // Cargar la relación si no está cargada
                    if (!$row->relationLoaded('categories')) {
                        $row->load('categories');
                    }
                    
                    if ($row->categories->isEmpty()) {
                        return '<span class="text-gray-400">Sin categorías</span>';
                    }
                    
                    $badges = $row->categories->map(function($category) {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                    <i class="fa-solid fa-tag mr-1"></i> ' . e($category->nombre) . '
                                </span>';
                    })->implode('');
                    
                    return $badges;
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
