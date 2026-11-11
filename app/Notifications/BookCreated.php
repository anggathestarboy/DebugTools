<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Book $book)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Buku baru: ' . $this->book->name)
            ->greeting('Halo!')
            ->line('Buku baru berhasil dibuat.')
            ->line('Judul: ' . $this->book->name)
            ->line('Deskripsi: ' . $this->book->desc)
            ->line('Tanggal terbit: ' . $this->book->publised_at)
            ->action('Lihat buku', url('/' . $this->book->id));
    }
}