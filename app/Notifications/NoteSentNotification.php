<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoteSentNotification extends Notification
{
    use Queueable;

    protected $note;
    /**
     * Create a new notification instance.
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['database', 'mail']; // Specify channels
        return ['database']; // Specify channels
    }

    public function toDatabase($notifiable)
    {
        return [
            'note_id' => $this->note->id,
            'note_title' => $this->note->title,
            'note_token' => $this->note->access_token,
            'sender' => $this->note->user->name,
            'created_at' => now(),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->subject('New Note Received')
    //         ->line('You have received a new note: ' . $this->note->title)
    //         ->action('View Note', route('notes.show', $this->note->id))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
