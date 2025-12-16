<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'article_id',
        'content',
        'parent_id'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // Relasi ke balasan (children)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at');
    }

    // Relasi ke parent comment
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Likes relationship
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    // Reports relationship
    public function reports()
    {
        return $this->hasMany(CommentReport::class);
    }

    // Helper to check if user liked the comment
    public function isLikedBy($user)
    {
        if (!$user)
            return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    // Hitung total balasan (recursive)
    public function getTotalRepliesCountAttribute()
    {
        $count = $this->replies->count();
        foreach ($this->replies as $reply) {
            $count += $reply->total_replies_count;
        }
        return $count;
    }
}
