<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the user's bookmarks.
     */
    public function index()
    {
        $bookmarks = Auth::user()->bookmarkedArticles()
            ->with(['category', 'user'])
            ->latest('bookmarks.created_at')
            ->paginate(10);

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle the bookmark status of an article.
     */
    public function toggle(Article $article)
    {
        $user = Auth::user();

        $bookmark = Bookmark::where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $is_bookmarked = false;
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            $is_bookmarked = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_bookmarked' => $is_bookmarked,
            ]);
        }

        return back()->with('success', $is_bookmarked ? 'Artikel berhasil disimpan.' : 'Artikel dihapus dari simpanan.');
    }
}
