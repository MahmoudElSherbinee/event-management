<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;


class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send An Event Reminder before 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events_start_soon = Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();
        $eventsCount = $events_start_soon->count();
        $eventsLabel = Str::plural('event', $eventsCount);
        $this->info("Found {$eventsCount} {$eventsLabel}.");
        $events_start_soon->each(
            fn($event) => $event->attendees->each(
                fn($attendee) =>
                $attendee->user->notify(
                    new EventReminderNotification($event)
                )
            )
        );


        $this->info('Reminder notification sent successfully!');
    }
}
