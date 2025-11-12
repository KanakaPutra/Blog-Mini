<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->is_admin == 1) {
            // LOGIKA SUDAH BENAR:
            // Admin hanya melihat artikelnya sendiri.
            // Ini sesuai permintaan "hanya artikel milik si penulis aja yang di tampilin".
            $articles = Article::with(['category', 'user'])
                ->where('user_id', $user->id) // <-- Kunci: Hanya ambil artikel milik user yang login
                ->latest()
                ->get();
        } else {
            // User biasa melihat semua artikel (untuk tampilan publik)
            $articles = Article::with(['category', 'user'])->latest()->get();
        }

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        // LOGIKA SUDAH BENAR: Hanya admin yang bisa membuat artikel
        if (Auth::user()->is_admin != 1) {
            abort(403, 'Anda tidak memiliki izin untuk membuat artikel.');
        }

        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // LOGIKA SUDAH BENAR: Hanya admin yang bisa menyimpan artikel
        if (Auth::user()->is_admin != 1) {
            abort(403, 'Anda tidak memiliki izin untuk menambah artikel.');
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('thumbnails', 'public')
            : null;

        Article::create([
            'user_id' => Auth::id(), // <-- Kunci: Artikel diikat ke ID user
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->input('content'),
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit(Article $article)
    {
        // LOGIKA SUDAH BENAR:
        // Cek ini ( $article->user_id !== Auth::id() ) memastikan
        // Admin2 tidak bisa edit artikel Admin1.
        if (Auth::user()->is_admin != 1 || $article->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengedit artikel ini.');
        }

        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        // LOGIKA SUDAH BENAR:
        // Cek ini ( $article->user_id !== Auth::id() ) memastikan
        // Admin2 tidak bisa update artikel Admin1.
        if (Auth::user()->is_admin != 1 || $article->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak memperbarui artikel ini.');
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('title', 'content', 'category_id');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $article->update($data);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Article $article)
    {
        // LOGIKA SUDAH BENAR:
        // Cek ini ( $article->user_id !== Auth::id() ) memastikan
        // Admin2 tidak bisa hapus artikel Admin1.
        if (Auth::user()->is_admin != 1 || $article->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menghapus artikel ini.');
        }

        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus!');
    }

    public function show(Article $article)
    {
        $article->load(['category', 'user', 'comments.user']);
        return view('articles.show', compact('article'));
    }
}