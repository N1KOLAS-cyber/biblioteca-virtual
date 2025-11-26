<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $isStaff = $user->hasRole('staff') && !$user->hasRole('admin');

        if ($isStaff) {
            return $this->staffDashboard();
        }

        return $this->adminDashboard();
    }

    /**
     * Dashboard para administradores
     */
    private function adminDashboard()
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

    /**
     * Dashboard para staff
     */
    private function staffDashboard()
    {
        // Usuarios activos vs inactivos
        $activeUsers = User::whereHas('roles', function($query) {
            $query->where('name', '!=', 'admin');
        })->count();
        
        $inactiveUsers = 0; // Por ahora no hay campo de activo/inactivo

        // Membresías activas
        $activeMemberships = DB::table('memberships')
            ->whereIn('status', ['active', 'trial'])
            ->count();

        // Membresías por vencer (próximos 7 días)
        $expiringMemberships = DB::table('memberships')
            ->whereIn('status', ['active', 'trial'])
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->count();

        // Membresías vencidas
        $expiredMemberships = DB::table('memberships')
            ->whereIn('status', ['active', 'trial'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->count();

        // Usuarios recientes (últimos 5)
        $recentUsers = User::whereHas('roles', function($query) {
            $query->where('name', '!=', 'admin');
        })->latest()->take(5)->get();

        // Membresías por vencer (detalles)
        $expiringMembershipsList = DB::table('memberships')
            ->join('users', 'memberships.user_id', '=', 'users.id')
            ->join('plans', 'memberships.plan_id', '=', 'plans.id')
            ->whereIn('memberships.status', ['active', 'trial'])
            ->whereNotNull('memberships.expires_at')
            ->whereBetween('memberships.expires_at', [now(), now()->addDays(7)])
            ->select('users.name', 'users.email', 'plans.name as plan_name', 'memberships.expires_at', 'memberships.status')
            ->orderBy('memberships.expires_at', 'asc')
            ->limit(5)
            ->get();

        $stats = [
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'active_memberships' => $activeMemberships,
            'expiring_memberships' => $expiringMemberships,
            'expired_memberships' => $expiredMemberships,
        ];

        return view('admin.staff.dashboard', compact('stats', 'recentUsers', 'expiringMembershipsList'));
    }
}
