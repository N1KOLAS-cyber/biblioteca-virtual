<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('author');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('sinopsis', 'like', "%{$search}%")
                  ->orWhereHas('author', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por autor
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Filtro por categoría
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $books = $query->latest()->paginate(12);
        $authors = Author::where('is_verified', true)->orderBy('nombre')->get();
        $categories = Category::where('is_active', true)->orderBy('nombre')->get();

        return view('catalog.index', compact('books', 'authors', 'categories'));
    }
}
