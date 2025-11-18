<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan halaman Super Admin settings + daftar kategori
    public function index()
    {
        $categories = Category::all();
        return view('superadmin.settings', compact('categories'));
    }

    // Menambahkan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Mengubah nama kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cek apakah ada perubahan
        if ($category->name === $request->name) {
            return redirect()->back()->with('info', 'Tidak ada perubahan pada kategori.');
        }

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diubah.');
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

    // Menampilkan artikel berdasarkan kategori (publik)
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $articles = $category->articles()->with(['user', 'comments'])->latest()->get();

        return view('categories.show', compact('category', 'articles'));
    }
}
