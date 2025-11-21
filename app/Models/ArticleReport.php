<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReport extends Model
{
    protected $fillable = ['article_id', 'user_id', 'reason'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

