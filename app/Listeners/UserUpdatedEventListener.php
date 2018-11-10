<?php

namespace App\Listeners;

use App\Mail\UserMailChanged;
use App\Events\UserUpdatedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUpdatedEventListener
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
     * @param  UserUpdatedEvent  $event
     * @return void
     */
    public function handle(UserUpdatedEvent $event)
    {
        $user = $event->user;
        if($user->isDirty('email'))
        {
            retry(5,function() use ($user) {
                Mail::to($user)->send( new UserMailChanged($user) );    
            },100);
            
        }
    }
}
