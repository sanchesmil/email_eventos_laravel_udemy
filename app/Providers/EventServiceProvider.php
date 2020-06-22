<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Mapeia 2 ouvintes (listeners) para o evento 'HomeEvent'
        'App\Events\HomeEvent' => [
            'App\Listeners\HomeEventListener',
            'App\Listeners\HomeEventListener2',
        ],

        // Mapeia o listener 'LoginListener' para evento de 'Login' do próprio laravel
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LoginListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
