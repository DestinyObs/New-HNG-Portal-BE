<?php

namespace App\Listeners;

use App\Events\PasswordChanged;
use App\Events\PasswordEmail;
use App\Mail\Auth\PasswordChangedNotification;
use App\Mail\Auth\PasswordResetLink;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;

class PasswordSubscriber
{
    public function sendPasswordResetLink(PasswordEmail $event): void
    {

        $url = config('services.base_url').'/reset-password?hash='.$event->token.'&email='.$event->user->email;

        // Send the password reset email
        Mail::to($event->user->email)->send(
            new PasswordResetLink($event->user, $url, $event->duration)
        );
    }

    public function sendPasswordChangedNotification(PasswordChanged $event): void
    {
        Mail::to($event->user->email)->send(new PasswordChangedNotification($event->user));
    }

    public function subscribe(Dispatcher $event): void
    {
        $event->listen(
            PasswordEmail::class,
            [PasswordSubscriber::class, 'sendPasswordResetLink']
        );

        $event->listen(
            PasswordChanged::class,
            [PasswordSubscriber::class, 'sendPasswordChangedNotification']
        );
    }
}
