<?php

namespace App\Listeners;

use App\Events\AnalysicEvent;
use App\Models\Analytics;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreEvent
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
    public function handle(AnalysicEvent $event): void
    {
        Analytics::create([
            'project_id' => $event->element->project_id,
            'element_id' => $event->element->id,
            'action' => $event->action,
            'event_data' =>  $event->data,
            'ip' => $event->request->ip(),
            'user_agent' => $event->request->userAgent(),
        ]);
    }
}
