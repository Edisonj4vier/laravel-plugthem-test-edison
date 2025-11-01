<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surveys = Survey::where('status', 'active')->get();

        $questionTemplates = [
            ['text' => '¿Cómo calificarías nuestro servicio?', 'type' => 'rating'],
            ['text' => '¿Qué tan probable es que recomiendes nuestro producto?', 'type' => 'rating'],
            ['text' => '¿Qué aspectos podemos mejorar?', 'type' => 'text'],
            ['text' => 'Cuéntanos tu experiencia', 'type' => 'text'],
            ['text' => '¿Usarías nuestro servicio nuevamente?', 'type' => 'select'],
            ['text' => '¿Cuál es tu nivel de satisfacción?', 'type' => 'rating'],
            ['text' => 'Comentarios adicionales', 'type' => 'text'],
            ['text' => '¿Recomendarías este servicio a un amigo?', 'type' => 'select'],
        ];

        foreach ($surveys as $survey) {
            // Crear 4-6 preguntas por encuesta
            $numQuestions = rand(4, 6);

            for ($i = 0; $i < $numQuestions; $i++) {
                $template = $questionTemplates[array_rand($questionTemplates)];

                Question::create([
                    'survey_id' => $survey->id,
                    'text' => $template['text'],
                    'type' => $template['type'],
                ]);
            }
        }

    }
}
