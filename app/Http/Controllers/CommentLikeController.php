<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentLikeController extends Controller
{
    public function toggle(Request $request, Comment $comment)
    {
        $user = $request->user();
        $existingLike = $comment->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $liked = true;

            // Trigger notification if liker is not the comment author
            if ($comment->user_id !== $user->id) {
                $comment->user->notify(new \App\Notifications\CommentLiked($user, $comment, $comment->article));
            }
        }

        return response()->json([
            'liked' => $liked,
            'count' => $comment->likes()->count()
        ]);
    }
}
