<?php

namespace App\Listeners;

use App\Events\ViewRecorded;
use App\Models\ProjectView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class LogView
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ViewRecorded $event): void
    {
        ProjectView::create([
            'project_id' => $event->project->id,
            'ip_address' => $event->ip_address,
        ]);
        Cache::forget("project_view_count_{$event->project->id}");
    }
}
