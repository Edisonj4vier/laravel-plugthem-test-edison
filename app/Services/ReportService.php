<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Survey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportService {
    /**
     * Generate a report for the given survey.
     *
     * @param Survey $survey
     * @return array
     */
    public function generateSurveyReport(Survey $survey): array
    {
        //definimos una clave de caché única para esta encuesta
        $cacheKey = "report_survey_{$survey->id}";

        // Definimos el tiempo de vida de la caché
        $ttl = now()->addMinutes(30);

        return Cache::remember($cacheKey, $ttl, function () use ($survey) {

        //Total de usuarios únicos que respondieron
        $totalUniqueUsers = $survey->answers()->distinct('user_id')->count();

        // Usamos withCount para cargar 'answers_count' en cada pregunta
        $responsesPerQuestion = $survey->questions()
            ->withCount('answers')
            ->get()
            ->mapWithKeys(function ($question) {
                // Formateamos como: "Texto de la Pregunta" => 5
                return [$question->text => $question->answers_count];
            });

            // Obtenemos los IDs solo de las preguntas 'rating' de ESTA encuesta
            $ratingQuestionIds = $survey->questions()
            ->where('type', 'rating')
            ->pluck('id');

            // Usamos DB::raw para asegurar que 'value' se trate como número
            $ratingAverage = Answer::whereIn('question_id', $ratingQuestionIds)
            ->avg(DB::raw('CAST(value AS DECIMAL(10,2))'));

            return [
                'survey_title' => $survey->title,
                'total_unique_respondents' => $totalUniqueUsers,
                'rating_questions_average' => $ratingAverage ? round($ratingAverage, 2) : 0,
                'responses_per_question' => $responsesPerQuestion,
            ];
        });
    }
}
