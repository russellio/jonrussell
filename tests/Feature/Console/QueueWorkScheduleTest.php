<?php

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;

test('the queue is drained by exactly one scheduled queue:work every minute', function () {
    $events = collect(app(Schedule::class)->events())
        ->filter(fn (Event $event): bool => str_contains((string) $event->command, 'queue:work'));

    expect($events)->toHaveCount(1);

    $event = $events->first();

    expect($event->command)->toContain('--stop-when-empty')
        ->and($event->expression)->toBe('* * * * *')
        ->and($event->withoutOverlapping)->toBeTrue()
        ->and($event->expiresAt)->toBe(5)
        ->and($event->runInBackground)->toBeTrue();
});
