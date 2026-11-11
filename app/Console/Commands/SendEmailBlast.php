<?php

namespace App\Console\Commands;

use App\Jobs\BlastEmailJob;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class SendEmailBlast extends Command
{
    protected $signature = 'email:blast
        {--per-second=30}
        {--limit=}
        {--subject=Info Terbaru}
        {--body=Halo, ini adalah email blast.}';

    protected $description = 'Kirim email blast lewat job batch dengan batas per detik';

    public function handle(): int
    {
        $perSecond = max(1, (int) $this->option('per-second'));
        $subject = (string) $this->option('subject');
        $body = (string) $this->option('body');

        $query = User::query()
            ->whereNotNull('email')
            ->select('id', 'name', 'email');

        if ($this->option('limit')) {
            $query->limit((int) $this->option('limit'));
        }

        $jobs = [];
        $index = 0;

        foreach ($query->cursor() as $user) {
            $delay = intdiv($index, $perSecond);

            $jobs[] = (new BlastEmailJob($user->email, $user->name, $subject, $body))
                ->delay(now()->addSeconds($delay));

            $index++;
        }

        if ($index === 0) {
            $this->warn('Tidak ada penerima.');
            return self::SUCCESS;
        }

        $batch = Bus::batch($jobs)
            ->name('Email blast ' . now()->format('Y-m-d H:i:s'))
            ->allowFailures()
            ->onQueue('emails')
            ->then(function (Batch $batch) {
                Log::info('Email blast selesai: ' . $batch->id);
            })
            ->finally(function (Batch $batch) {
                Log::info('Email blast diproses', [
                    'total' => $batch->totalJobs,
                    'gagal' => $batch->failedJobs,
                ]);
            })
            ->dispatch();

        $this->info("Batch dibuat: {$batch->id}");
        $this->info("Total email: {$index}");
        $this->info('Estimasi durasi: ' . (intdiv($index - 1, $perSecond) + 1) . ' detik');

        return self::SUCCESS;
    }
}