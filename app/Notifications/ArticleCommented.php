<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArticleCommented extends Notification
{
    use Queueable;

    protected $commenter;
    protected $comment;
    protected $article;

    /**
     * Create a new notification instance.
     */
    public function __construct($commenter, $comment, $article)
    {
        $this->commenter = $commenter;
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
        $isReply = !is_null($this->comment->parent_id);

        // Determine the message based on who is receiving it
        if ($isReply) {
            if ($this->comment->parent->user_id === $notifiable->id) {
                $message = "{$this->commenter->name} membalas komentar Anda di artikel: {$this->article->title}";
            } else {
                $message = "{$this->commenter->name} membalas komentar di artikel Anda: {$this->article->title}";
            }
        } else {
            $message = "{$this->commenter->name} mengomentari artikel Anda: {$this->article->title}";
        }

        return [
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'comment_id' => $this->comment->id,
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'is_reply' => $isReply,
            'message' => $message,
        ];
    }
}
