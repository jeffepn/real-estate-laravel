<?php

namespace Jeffpereira\RealEstate;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Jeffpereira\RealEstate\Events\BusinessPropertyFinalizedEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        BusinessPropertyFinalizedEvent::class => [],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
