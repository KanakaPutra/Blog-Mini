<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentReportController extends Controller
{
    public function store(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $comment->reports()->create([
            'user_id' => $request->user()->id,
            'reason' => $validated['reason'],
        ]);

        return response()->json(['message' => 'Comment reported successfully.']);
    }
}
