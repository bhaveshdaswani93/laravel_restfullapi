<?php

namespace App\Listeners;

use App\Mail\UserCreated;
use App\Events\UserCreatedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        $user = $event->user;
        retry(5,function() use ($user) {
                Mail::to($user)->send(new UserCreated($user));        
            },100);
        
    }
}
