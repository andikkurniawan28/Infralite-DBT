<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunScheduledBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-scheduled-backups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $currentDay = $now->format('l'); // e.g. Monday
        $currentHour = $now->format('H:i'); // e.g. 14:00

        $schedules = \App\Models\Schedule::where('day', $currentDay)
            ->where('hour', $currentHour)
            ->get();

        foreach ($schedules as $schedule) {
            \Illuminate\Support\Facades\Http::get(
                route('scheduled_backup.process', $schedule->id)
            );
        }

        $this->info("Processed " . $schedules->count() . " backup(s).");
    }
}
