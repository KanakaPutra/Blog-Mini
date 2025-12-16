<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    protected $fillable = ['user_id', 'comment_id', 'reason', 'status'];
}
