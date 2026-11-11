<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BlastEmailJob implements ShouldQueue
{
    use Batchable, Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
        public string $email,
        public ?string $name,
        public string $subject,
        public string $body,
    ) {
    }

    public function backoff(): array
    {
        return [5, 15];
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        Mail::html(
            nl2br(e($this->body)),
            function ($message) {
                $message->to($this->email, $this->name)->subject($this->subject);
            }
        );
    }

    public function failed(Throwable $e): void
    {
        Log::error('Email gagal ke ' . $this->email . ': ' . $e->getMessage());
    }
}