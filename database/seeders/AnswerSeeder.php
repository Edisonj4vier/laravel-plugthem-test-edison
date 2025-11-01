<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surveys = Survey::where('status', 'active')->with('questions')->get();
        $users = User::all();

        foreach ($surveys as $survey) {
            // Algunos usuarios responderán esta encuesta (entre 50-80% de los usuarios)
            $respondents = $users->random(rand(3, 4));

            foreach ($respondents as $user) {
                // Cada usuario responde todas o algunas preguntas
                $questionsToAnswer = $survey->questions->random(rand(3, $survey->questions->count()));

                foreach ($questionsToAnswer as $question) {
                    // Generar respuesta según el tipo de pregunta
                    $value = $this->generateAnswer($question->type);

                    Answer::create([
                        'user_id' => $user->id,
                        'survey_id' => $survey->id,
                        'question_id' => $question->id,
                        'value' => $value,
                    ]);
                }
            }
        }
    }

    private function generateAnswer(string $type): string
    {
        return match ($type) {
            'rating' => (string) rand(1, 5),
            'select' => collect(['Sí', 'No', 'Tal vez'])->random(),
            'text' => collect([
                'Excelente servicio',
                'Muy buena experiencia',
                'Podría mejorar en algunos aspectos',
                'La atención fue rápida y eficiente',
                'Me gustó mucho',
                'Satisfecho con el resultado',
                'Superó mis expectativas',
            ])->random(),
        };
    }
}
