<?php

namespace App\Providers;

use App\Events\ViewRecorded;
use App\Listeners\LogView;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
   protected $listen = [
        ViewRecorded::class => [
            LogView::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
