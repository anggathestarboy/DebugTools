<?php

use App\Jobs\FlushCacheJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:prune-failed --hours=48')->daily();

Schedule::job(new FlushCacheJob)->hourly();

Schedule::call(function () {
    Log::info('Task terjadwal berjalan');
})->everyMinute()->name('log-setiap-menit');

Schedule::command('inspire')->everyFiveMinutes();
