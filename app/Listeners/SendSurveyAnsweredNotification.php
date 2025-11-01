<?php

namespace App\Listeners;

use App\Events\SurveyAnswered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSurveyAnsweredNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * Esto se ejecuta automáticamente cuando el evento SurveyAnswered es disparado.
     */
    public function handle(SurveyAnswered $event): void
    {
        Log::info("Usuario {$event->user->id} respondió la encuesta {$event->survey->id} el " . now());
    }
}
