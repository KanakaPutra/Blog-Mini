<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleLiked extends Notification
{
    use Queueable;

    protected $liker;
    protected $article;

    /**
     * Create a new notification instance.
     */
    public function __construct($liker, $article)
    {
        $this->liker = $liker;
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
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'message' => "{$this->liker->name} menyukai artikel Anda: {$this->article->title}",
        ];
    }
}
