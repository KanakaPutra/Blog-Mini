<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'thumbnail',
    ];

    // ==========================
    //        RELATION
    // ==========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // LIKE & DISLIKE
    public function likes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    // REPORT
    public function reports()
    {
        return $this->hasMany(ArticleReport::class);
    }

    // ==========================
    //         HELPERS
    // ==========================

    public function totalLikes(): int
    {
        return $this->likes()
            ->where('type', 'like')
            ->count();
    }

    public function totalDislikes(): int
    {
        return $this->likes()
            ->where('type', 'dislike')
            ->count();
    }

    public function totalReports(): int
    {
        return $this->reports()->count();
    }

    // Dapatkan reaksi user (like/dislike)
    public function userReaction($userId)
    {
        return $this->likes()
            ->where('user_id', $userId)
            ->first();
    }

    // === Reaction Checkers ===

    public function isLikedBy($user): bool
    {
        return $user
            ? $this->likes()
                ->where('user_id', $user->id)
                ->where('type', 'like')
                ->exists()
            : false;
    }

    public function isDislikedBy($user): bool
    {
        return $user
            ? $this->likes()
                ->where('user_id', $user->id)
                ->where('type', 'dislike')
                ->exists()
            : false;
    }

    public function isReportedBy($user): bool
    {
        return $user
            ? $this->reports()
                ->where('user_id', $user->id)
                ->exists()
            : false;
    }
}
