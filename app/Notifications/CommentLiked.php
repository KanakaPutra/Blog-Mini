<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentLiked extends Notification
{
    use Queueable;

    protected $liker;
    protected $comment;
    protected $article;

    /**
     * Create a new notification instance.
     */
    public function __construct($liker, $comment, $article)
    {
        $this->liker = $liker;
        $this->comment = $comment;
        $this->article = $article;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'comment_id' => $this->comment->id,
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'message' => "{$this->liker->name} menyukai komentar Anda di artikel: {$this->article->title}",
        ];
    }
}
