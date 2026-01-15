<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleLike;
use App\Models\ArticleReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // ADMIN & SUPER ADMIN
        if ($user && $user->is_admin >= 1) {
            $query = Article::with(['category', 'user']);

            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // SUPER ADMIN (is_admin == 2)
            if ($user->is_admin == 2) {
                if ($request->filter === 'mine') {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('suspended', false);
                }
            }
            // ADMIN (is_admin == 1) -> Defaultnya cuma lihat punyanya sendiri
            else {
                $query->where('user_id', $user->id);
            }

            // FILTER LOGIC (berlaku untuk keduanya dalam konteks masing-masing)
            if ($request->filter === 'pending') {
                $query->where(function ($q) {
                    $q->where('status', 'draft')
                        ->orWhere(function ($sq) {
                            $sq->where('status', 'published')
                                ->where('published_at', '>', now());
                        });
                });
            } else {
                // Default view: Hanya yang sudah live (berdasarkan context query di atas)
                $query->published();
            }

            $articles = $query->latest()->get();
            return view('articles.index', compact('articles'));
        }

        // USER biasa (Public View)
        $query = Article::with(['category', 'user'])
            ->published()
            ->where('suspended', false);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $articles = $query->latest()->get();
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
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'articles/' . $filename;

            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_SERVICE_ROLE_KEY');

            if (!$supabaseUrl || !$supabaseKey) {
                return back()->withErrors(['thumbnail' => 'Konfigurasi Supabase belum lengkap di file .env.'])->withInput();
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $supabaseKey,
            ])->attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $filename
                )->post(
                    rtrim($supabaseUrl, '/') . '/storage/v1/object/images/' . $path
                );

            if ($response->successful()) {
                $thumbnailPath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/images/' . $path;
            } else {
                $error = $response->json('error') ?? $response->json('message') ?? $response->body();
                return back()->withErrors(['thumbnail' => 'Gagal mengunggah ke Supabase: ' . $error])->withInput();
            }
        }

        $data = $request->only('title', 'content', 'category_id', 'status', 'published_at');
        $data['user_id'] = Auth::id();
        $data['thumbnail'] = $thumbnailPath;

        // Jika status published tapi published_at kosong, set ke sekarang
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

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
                'status' => 'required|in:draft,published',
                'published_at' => 'nullable|date',
            ]);

            $data = $request->only('title', 'content', 'category_id', 'status', 'published_at');

            // Jika status published tapi published_at kosong, set ke sekarang
            if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'articles/' . $filename;

                $supabaseUrl = env('SUPABASE_URL');
                $supabaseKey = env('SUPABASE_SERVICE_ROLE_KEY');

                if (!$supabaseUrl || !$supabaseKey) {
                    return back()->withErrors(['thumbnail' => 'Konfigurasi Supabase belum lengkap di file .env.'])->withInput();
                }

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $supabaseKey,
                ])->attach(
                        'file',
                        file_get_contents($file->getRealPath()),
                        $filename
                    )->post(
                        rtrim($supabaseUrl, '/') . '/storage/v1/object/images/' . $path
                    );

                if ($response->successful()) {
                    $data['thumbnail'] = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/images/' . $path;
                } else {
                    $error = $response->json('error') ?? $response->json('message') ?? $response->body();
                    return back()->withErrors(['thumbnail' => 'Gagal mengunggah ke Supabase: ' . $error])->withInput();
                }
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
        $user = Auth::user();

        // Visibility check:
        // Jika belum published, hanya author atau super admin (is_admin == 2) yang bisa lihat.
        if (!$article->isPublished()) {
            if (!$user || ($user->id !== $article->user_id && $user->is_admin != 2)) {
                abort(403, 'Artikel ini belum dipublikasikan atau masih berupa draft.');
            }
        }

        $article->load(['category', 'user', 'comments.user']);
        return view('articles.show', compact('article'));
    }

    public function history(Request $request)
    {
        $type = $request->query('type', 'like');
        $user = Auth::user();

        if ($type === 'comment') {
            // Ambil komentar user beserta artikelnya
            $comments = \App\Models\Comment::where('user_id', $user->id)
                ->with(['article.category', 'article.user'])
                ->latest()
                ->get();

            return view('articles.history', compact('comments', 'type'));
        } else {
            $articles = $user->likedArticles()->with(['category', 'user'])->latest('article_likes.created_at')->get();
            return view('articles.history', compact('articles', 'type'));
        }
    }

    // ============================================================
    //                          LIKE
    // ============================================================

    public function like($id, Request $request)
    {
        $article = Article::findOrFail($id);
        $userId = Auth::id();

        $existing = $article->likes()->where('user_id', $userId)->first();
        $action = '';

        if ($existing) {
            if ($existing->type === 'like') {
                $existing->delete(); // batal like
                $action = 'unliked';
            } else {
                $existing->update(['type' => 'like']); // ubah dislike → like
                $action = 'liked';
            }
        } else {
            $article->likes()->create([
                'user_id' => $userId,
                'type' => 'like',
            ]);
            $action = 'liked';
        }

        // Trigger notification if it's a like and not from the author
        if ($action === 'liked' && $article->user_id !== $userId) {
            $article->user->notify(new \App\Notifications\ArticleLiked(auth()->user(), $article));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'total_likes' => $article->totalLikes(),
                'is_liked' => $article->isLikedBy(Auth::user()),
                'is_disliked' => $article->isDislikedBy(Auth::user()),
            ]);
        }

        return back();
    }

    // ============================================================
    //                         DISLIKE
    // ============================================================

    public function dislike($id, Request $request)
    {
        $article = Article::findOrFail($id);
        $userId = Auth::id();

        $existing = $article->likes()->where('user_id', $userId)->first();
        $action = '';

        if ($existing) {
            if ($existing->type === 'dislike') {
                $existing->delete(); // batal dislike
                $action = 'undisliked';
            } else {
                $existing->update(['type' => 'dislike']); // ubah like → dislike
                $action = 'disliked';
            }
        } else {
            $article->likes()->create([
                'user_id' => $userId,
                'type' => 'dislike',
            ]);
            $action = 'disliked';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'total_likes' => $article->totalLikes(),
                'is_liked' => $article->isLikedBy(Auth::user()),
                'is_disliked' => $article->isDislikedBy(Auth::user()),
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

        $request->validate([
            'reason' => 'required|string',
            'details' => 'nullable|string',
        ]);

        ArticleReport::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        return back()->with('success', 'Laporan terkirim!');
    }
}
