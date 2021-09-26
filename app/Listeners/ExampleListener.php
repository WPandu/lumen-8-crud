<?php

namespace App\Listeners;

use App\Events\ExampleEvent;

class ExampleListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //noop
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\ExampleEvent $event
     * @return void
     */
    //phpcs:ignore
    public function handle(ExampleEvent $event)
    {
        //noop
    }
}
