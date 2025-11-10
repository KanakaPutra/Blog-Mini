<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id'    => Auth::id(),
            'article_id' => $request->article_id,
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}
