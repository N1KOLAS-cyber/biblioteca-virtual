<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'users' => User::count(),
            'books' => Book::count(),
            'authors' => Author::count(),
            'categories' => Category::where('is_active', true)->count(),
            'books_free' => Book::where('is_free', true)->count(),
            'books_premium' => Book::where('is_free', false)->count(),
            'books_featured' => Book::where('is_featured', true)->count(),
        ];

        // Actividad reciente
        $recentActivities = collect();

        // Usuarios recientes (últimos 3)
        $recentUsers = User::latest()->take(3)->get();
        foreach ($recentUsers as $user) {
            $recentActivities->push([
                'type' => 'user',
                'icon' => 'fa-solid fa-users',
                'icon_color' => 'text-blue-600',
                'bg_color' => 'bg-blue-100',
                'message' => 'Nuevo usuario registrado',
                'details' => $user->name,
                'time' => $user->created_at,
            ]);
        }

        // Libros recientes (últimos 3)
        $recentBooks = Book::latest()->take(3)->get();
        foreach ($recentBooks as $book) {
            $recentActivities->push([
                'type' => 'book',
                'icon' => 'fa-solid fa-book',
                'icon_color' => 'text-green-600',
                'bg_color' => 'bg-green-100',
                'message' => 'Nuevo libro creado',
                'details' => $book->titulo,
                'time' => $book->created_at,
            ]);
        }

        // Ordenar por fecha más reciente y tomar los últimos 5
        $recentActivities = $recentActivities->sortByDesc('time')->take(5)->values();

        return view('admin.dashboard', compact('stats', 'recentActivities'));
    }
}
