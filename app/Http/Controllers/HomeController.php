<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $articles = Article::with('category', 'user')->latest()->take(5)->get();
        return view('home', compact('articles'));
    }

    public function show(Article $article)
    {
        $article->load(['comments.user', 'category', 'user']);
        return view('articles.show', compact('article'));
    }
}
