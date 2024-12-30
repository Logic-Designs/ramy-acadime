<?php

namespace App\Listeners;

use App\Events\UserRegistered as EventsUserRegistered;
use App\Models\User;
use App\Notifications\UserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewUserNotification
{
    public function __construct()
    {
        //
    }

    public function handle(EventsUserRegistered $event)
    {
        // Notify admin(s) or other users
        $adminUsers = User::role('admin', 'sanctum')->get();

        foreach ($adminUsers as $admin) {
            $admin->notify(new UserRegistered($event->user));
        }
    }
}
