<?php

declare(strict_types=1);

namespace App\Notifications\Post;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostPublished extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Post $post, private readonly bool $wasDelayed)
    {
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->wasDelayed ? 'Scheduled Post published !' : 'Post published !';
        $title = $this->wasDelayed ? 'Your Scheduled Post has been published !' : 'Your Post has been published !';

        return (new MailMessage())
            ->subject($subject)
            ->line($title)
            ->line('You can view it here:')
            ->action('View Post', route('posts.show', $this->post->slug));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}
