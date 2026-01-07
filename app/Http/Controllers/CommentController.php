<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id', // untuk reply
            'depth' => 'nullable|integer', // terima depth dari frontend
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'article_id' => $request->article_id,
            'content' => $request->input('content'),
            'parent_id' => $request->parent_id, // simpan parent
        ]);

        $article = $comment->article;
        $commenter = auth()->user();

        // 1. Notify article author for ANY comment/reply (if not by the author themselves)
        if ($article->user_id !== $commenter->id) {
            $article->user->notify(new \App\Notifications\ArticleCommented($commenter, $comment, $article));
        }

        // 2. Notify parent comment author if it's a reply (if not by the parent author themselves)
        // AND if the parent author is NOT the article author (to avoid double notification)
        if (
            $comment->parent_id &&
            $comment->parent->user_id !== $commenter->id &&
            $comment->parent->user_id !== $article->user_id
        ) {
            $comment->parent->user->notify(new \App\Notifications\ArticleCommented($commenter, $comment, $article));
        }

        if ($request->wantsJson()) {
            // Load relationships needed for the view
            $comment->load('user', 'replies');

            // Determine depth and parent author for the view
            // Depth yang dikirim adalah depth parent, jadi depth comment baru adalah parent + 1
            // Jika tidak ada parent, depth = 0
            $depth = $request->input('depth', 0) + 1;
            $parentAuthor = $comment->parent ? $comment->parent->user->name : null;

            // Render the component
            $html = view('components.comment', [
                'comment' => $comment,
                'depth' => $depth,
                'parentAuthor' => $parentAuthor
            ])->render();

            return response()->json([
                'html' => $html,
                'message' => 'Comment posted successfully'
            ]);
        }

        return back()->with(
            'success',
            $request->parent_id
            ? 'Balasan berhasil dikirim.'
            : 'Komentar berhasil dikirim.'
        );
    }

    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment
        ]);
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
