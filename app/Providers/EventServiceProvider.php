<?php

namespace App\Providers;

use App\Events\SurveyAnswered;
use App\Listeners\ClearSurveyReportCache;
use App\Listeners\SendSurveyAnsweredNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SurveyAnswered::class => [
            SendSurveyAnsweredNotification::class,
            ClearSurveyReportCache::class,
        ],
    ];

    /**
     * Bootstrap services.
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
