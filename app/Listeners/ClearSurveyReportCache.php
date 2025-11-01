<?php

namespace App\Listeners;

use App\Events\SurveyAnswered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearSurveyReportCache
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
     */
    public function handle(SurveyAnswered $event): void
    {
            // Borra la clave de caché que definimos en el ReportService
            $surveyId = $event->survey->id;
            $cacheKey = "report_survey_{$surveyId}";
            Cache::forget($cacheKey);

            Log::info("Caché de reporte borrada para encuesta: {$event->survey->id}");
    }
}
