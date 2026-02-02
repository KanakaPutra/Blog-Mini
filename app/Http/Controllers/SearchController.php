<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SearchController extends Controller
{
    /**
     * Handle the global search.
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $type = $request->query('type', 'all');

        if (!$query) {
            return view('search.index', [
                'users' => [],
                'articles' => [],
                'query' => '',
                'type' => 'all'
            ]);
        }

        $users = collect();
        $articles = collect();

        // Search Users
        if ($type === 'all' || $type === 'users') {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->take(6) // Limit results
                ->get();
        }

        // Search Articles
        if ($type === 'all' || $type === 'articles') {
            $articles = Article::with(['user', 'category'])
                ->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%")
                ->published() // Ensure we only show published articles
                ->where('suspended', false)
                ->latest()
                ->take(9)
                ->get();
        }

        return view('search.index', compact('users', 'articles', 'query', 'type'));
    }
}
