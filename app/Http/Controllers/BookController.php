<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('author');
        
        // Verificar si el usuario puede leer el libro
        $canRead = false;
        $user = auth()->user();
        
        if ($book->is_free) {
            $canRead = true;
        } elseif ($user) {
            // Verificar si tiene membresía activa
            $membership = DB::table('memberships')
                ->where('user_id', $user->id)
                ->whereIn('status', ['active', 'trial'])
                ->latest('started_at')
                ->first();
            
            if ($membership) {
                $canRead = true;
            }
        }
        
        // Verificar si es favorito
        $isFavorite = false;
        if ($user) {
            $isFavorite = DB::table('favorites')
                ->where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->exists();
        }
        
        return view('books.show', compact('book', 'canRead', 'isFavorite'));
    }

    /**
     * Leer libro
     */
    public function read(Book $book)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Verificar acceso
        if (!$book->is_free) {
            $membership = DB::table('memberships')
                ->where('user_id', $user->id)
                ->whereIn('status', ['active', 'trial'])
                ->latest('started_at')
                ->first();
            
            if (!$membership) {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Acceso denegado',
                    'text' => 'Necesitas una membresía activa para leer este libro.',
                ]);
                return redirect()->route('catalog.index');
            }
        }
        
        // Registrar en historial de lectura
        DB::table('reading_history')->updateOrInsert(
            [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ],
            [
                'last_read_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        // Incrementar contador de lecturas
        $book->increment('reads_count');
        
        return view('books.read', compact('book'));
    }

    /**
     * Agregar/quitar favorito
     */
    public function toggleFavorite(Book $book)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        
        $exists = DB::table('favorites')
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->exists();
        
        if ($exists) {
            DB::table('favorites')
                ->where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->delete();
            $book->decrement('favorites_count');
            $message = 'Libro removido de favoritos';
        } else {
            DB::table('favorites')->insert([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $book->increment('favorites_count');
            $message = 'Libro agregado a favoritos';
        }
        
        return response()->json(['message' => $message, 'isFavorite' => !$exists]);
    }
}
