<?php

namespace App\Providers;

use App\Events\OtpGenerated;
use App\Listeners\OtpListener;
use App\Events\IncorrectPinEvent;
use App\Events\SubscriptionReminder;
use App\Listeners\PasswordSubscriber;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\IncorrectPinListener;
use App\Listeners\TransactionSubscriber;
use App\Listeners\WalletEventSubscriber;
use App\Listeners\CooperativeEventListener;
use App\Listeners\Auth\UserAuthenticationSubscriber;
use App\Listeners\SubscriptionSubscriber;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
     
    ];

    protected $subscribe = [

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
