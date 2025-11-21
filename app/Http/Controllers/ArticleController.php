<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleLike;
use App\Models\ArticleReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // SUPER ADMIN
        if ($user && $user->is_admin == 2) {
            if ($request->filter === 'mine') {
                $articles = Article::with(['category', 'user'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();
            } else {
                $articles = Article::with(['category', 'user'])->latest()->get();
            }

            return view('articles.index', compact('articles'));
        }

        // ADMIN → hanya artikelnya sendiri
        if ($user && $user->is_admin == 1) {
            $articles = Article::with(['category', 'user'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return view('articles.index', compact('articles'));
        }

        // USER biasa
        $articles = Article::with(['category', 'user'])->latest()->get();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        if (Auth::user()->is_admin < 1) {
            abort(403);
        }

        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->is_admin < 1) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
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

        if ($user->is_admin == 2 || ($user->is_admin == 1 && $article->user_id == $user->id)) {
            $categories = Category::all();
            return view('articles.edit', compact('article', 'categories'));
        }

        abort(403);
    }

    public function update(Request $request, Article $article)
    {
        $user = Auth::user();

        if ($user->is_admin == 2 || ($user->is_admin == 1 && $article->user_id == $user->id)) {

            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'nullable|exists:categories,id',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $data = $request->only('title', 'content', 'category_id');

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $article->update($data);

            return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui!');
        }

        abort(403);
    }

    public function destroy(Article $article)
    {
        $user = Auth::user();

        if ($user->is_admin == 2 || ($user->is_admin == 1 && $article->user_id == $user->id)) {
            $article->delete();
            return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus!');
        }

        abort(403);
    }

    public function show(Article $article)
    {
        $article->load(['category', 'user', 'comments.user']);
        return view('articles.show', compact('article'));
    }

    // ============================================================
    //                          LIKE
    // ============================================================

    public function like($id)
    {
        $article = Article::findOrFail($id);
        $userId = Auth::id();

        $existing = $article->likes()->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->type === 'like') {
                $existing->delete(); // batal like
            } else {
                $existing->update(['type' => 'like']); // ubah dislike → like
            }
        } else {
            $article->likes()->create([
                'user_id' => $userId,
                'type'    => 'like',
            ]);
        }

        return back();
    }

    // ============================================================
    //                         DISLIKE
    // ============================================================

    public function dislike($id)
    {
        $article = Article::findOrFail($id);
        $userId = Auth::id();

        $existing = $article->likes()->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->type === 'dislike') {
                $existing->delete(); // batal dislike
            } else {
                $existing->update(['type' => 'dislike']); // ubah like → dislike
            }
        } else {
            $article->likes()->create([
                'user_id' => $userId,
                'type'    => 'dislike',
            ]);
        }

        return back();
    }


    // ============================================================
    //                         REPORT
    // ============================================================

    public function report($id, Request $request)
    {
        $article = Article::findOrFail($id);

        ArticleReport::create([
            'article_id' => $article->id,
            'user_id'    => Auth::id(),
            'reason'     => $request->reason ?? 'No reason provided',
        ]);

        return back()->with('success', 'Laporan terkirim!');
    }
}
