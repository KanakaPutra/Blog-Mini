<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // =========================================================
        // 🔹 SUPER ADMIN → bisa filter "Artikel Saya"
        // =========================================================
        if ($user && $user->is_admin == 2) {
            if ($request->filter === 'mine') {
                $articles = Article::with(['category', 'user'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();
            } else {
                // default → semua artikel
                $articles = Article::with(['category', 'user'])->latest()->get();
            }

            return view('articles.index', compact('articles'));
        }


        // =========================================================
        // 🔹 ADMIN (is_admin = 1) → hanya artikelnya sendiri
        // =========================================================
        if ($user && $user->is_admin == 1) {
            $articles = Article::with(['category', 'user'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return view('articles.index', compact('articles'));
        }


        // =========================================================
        // 🔹 User biasa (is_admin = 0) → semua artikel publik
        // =========================================================
        $articles = Article::with(['category', 'user'])->latest()->get();

        return view('articles.index', compact('articles'));
    }



    public function create()
    {
        if (Auth::user()->is_admin < 1) {
            abort(403, 'Anda tidak memiliki izin untuk membuat artikel.');
        }

        $categories = Category::all();

        return view('articles.create', compact('categories'));
    }



    public function store(Request $request)
    {
        if (Auth::user()->is_admin < 1) {
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

        $data = $request->only('title', 'content', 'category_id');
        $data['user_id'] = Auth::id();
        $data['thumbnail'] = $thumbnailPath;

        Article::create($data);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil ditambahkan!');
    }



    public function edit(Article $article)
    {
        $user = Auth::user();

        // Super admin bebas edit
        if ($user->is_admin == 2) {
            $categories = Category::all();
            return view('articles.edit', compact('article', 'categories'));
        }

        // Admin cuma edit artikel sendiri
        if ($user->is_admin == 1 && $article->user_id == $user->id) {
            $categories = Category::all();
            return view('articles.edit', compact('article', 'categories'));
        }

        abort(403, 'Anda tidak berhak mengedit artikel ini.');
    }



    public function update(Request $request, Article $article)
    {
        $user = Auth::user();

        if ($user->is_admin == 2 || ($user->is_admin == 1 && $article->user_id == $user->id)) {

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

        abort(403, 'Anda tidak berhak memperbarui artikel ini.');
    }



    public function destroy(Article $article)
    {
        $user = Auth::user();

        if ($user->is_admin == 2 || ($user->is_admin == 1 && $article->user_id == $user->id)) {
            $article->delete();
            return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus!');
        }

        abort(403, 'Anda tidak berhak menghapus artikel ini.');
    }



    public function show(Article $article)
    {
        $article->load(['category', 'user', 'comments.user']);

        return view('articles.show', compact('article'));
    }
}
