<?php

namespace App\Providers;

use App\Events\AnalysicEvent;
use App\Events\ViewRecorded;
use App\Listeners\LogView;
use App\Listeners\StoreEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
   protected $listen = [
        ViewRecorded::class => [
            LogView::class,
        ],
        AnalysicEvent::class => [
            StoreEvent::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
