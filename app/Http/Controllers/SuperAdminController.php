<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    /**
     * 🔹 Halaman Manage Users (Super Admin)
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan role (User/Admin/Super Admin)
        if ($request->filled('role')) {
            $query->where('is_admin', $request->role);
        }

        // Urutkan user: Super Admin > Admin > User, lalu berdasarkan waktu pembuatan terbaru
        $users = $query->orderByDesc('is_admin')->latest()->paginate(7);

        return view('superadmin.users', compact('users'));
    }

    /**
     * 🔹 Halaman Settings (Super Admin)
     */
    public function settings()
    {
        // Semua kategori untuk CRUD kategori
        $categories = Category::all();

        // Statistik user
        $userCount = User::count();
        $superAdminCount = User::where('is_admin', 2)->count();
        $adminCount = User::where('is_admin', 1)->count();
        $normalUserCount = User::where('is_admin', 0)->count();

        // Statistik artikel per user
        $articlePerUser = Article::select('user_id')
            ->selectRaw('count(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        return view('superadmin.settings', compact(
            'categories',
            'userCount',
            'superAdminCount',
            'adminCount',
            'normalUserCount',
            'articlePerUser'
        ));
    }

    /**
     * 🔹 Ban user
     */
    public function ban(User $user)
    {
        if (! Auth::check()) {
            return back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa memban diri sendiri.');
        }

        if ($user->is_admin == 2) {
            return back()->with('error', 'Anda tidak bisa memban sesama Super Admin.');
        }

        $user->update(['banned' => true]);

        return back()->with('success', "{$user->name} berhasil diban.");
    }

    /**
     * 🔹 Unban user
     */
    public function unban(User $user)
    {
        if (! Auth::check()) {
            return back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        if ($user->is_admin == 2) {
            return back()->with('error', 'Tidak bisa mengubah status Super Admin.');
        }

        $user->update(['banned' => false]);

        return back()->with('success', "{$user->name} berhasil diunban.");
    }
}
