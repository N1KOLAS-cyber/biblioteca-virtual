<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Si el usuario está autenticado
        if (auth()->check()) {
            // Si es admin o staff, redirigir al panel de administración
            if (auth()->user()->hasRole(['admin', 'staff'])) {
                return redirect()->route('admin.dashboard');
            }
            
            // Para usuarios normales, mostrar contenido del catálogo
            // Obtener libros destacados o recientes
            $featuredBooks = Book::with('author')
                ->where('is_featured', true)
                ->latest()
                ->limit(8)
                ->get();
            
            // Si no hay destacados, mostrar los más recientes
            if ($featuredBooks->isEmpty()) {
                $featuredBooks = Book::with('author')
                    ->latest()
                    ->limit(8)
                    ->get();
            }
            
            // Obtener categorías activas
            $categories = Category::where('is_active', true)
                ->orderBy('nombre')
                ->limit(6)
                ->get();
            
            // Obtener autores verificados
            $authors = Author::where('is_verified', true)
                ->orderBy('nombre')
                ->limit(6)
                ->get();
            
            return view('home.authenticated', compact('featuredBooks', 'categories', 'authors'));
        }
        
        // Para usuarios no autenticados, mostrar página de bienvenida
        return view('home.index');
    }
}

