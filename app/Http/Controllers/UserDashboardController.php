<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Obtener plan actual
        $membership = DB::table('memberships')
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'trial'])
            ->latest('started_at')
            ->first();
        
        $currentPlan = null;
        if ($membership) {
            $currentPlan = Plan::find($membership->plan_id);
        }
        
        // Libros favoritos
        $favorites = DB::table('favorites')
            ->join('books', 'favorites.book_id', '=', 'books.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->where('favorites.user_id', $user->id)
            ->select('books.*', 'authors.nombre as author_name')
            ->latest('favorites.created_at')
            ->limit(6)
            ->get();
        
        // Historial de lectura
        $readingHistory = DB::table('reading_history')
            ->join('books', 'reading_history.book_id', '=', 'books.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->where('reading_history.user_id', $user->id)
            ->select('books.*', 'authors.nombre as author_name', 'reading_history.progress', 'reading_history.last_read_at')
            ->orderBy('reading_history.last_read_at', 'desc')
            ->limit(6)
            ->get();
        
        return view('user.dashboard', compact('currentPlan', 'favorites', 'readingHistory'));
    }
}
