<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        return view('superadmin.settings');
    }

    /**
     * 🔹 Ban user
     */
    public function ban(User $user)
    {
        if (!Auth::check()) {
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
        if (!Auth::check()) {
            return back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        if ($user->is_admin == 2) {
            return back()->with('error', 'Tidak bisa mengubah status Super Admin.');
        }

        $user->update(['banned' => false]);

        return back()->with('success', "{$user->name} berhasil diunban.");
    }
}
