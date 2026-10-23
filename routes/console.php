<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Drain the queue once a minute from www-data's schedule:run cron instead of
 * keeping a resident systemd worker. The prod box has 1 GB RAM and the queue
 * is nearly idle, so ~80 MB of always-on worker isn't worth it; jobs wait up
 * to 60 s instead. withoutOverlapping(5) caps the lock at 5 minutes rather
 * than the default 24 h, so a worker killed mid-run (e.g. by the OOM killer)
 * can't stall the queue for a day.
 */
Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=3')
    ->everyMinute()
    ->withoutOverlapping(5)
    ->runInBackground();
