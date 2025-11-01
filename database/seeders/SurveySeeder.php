<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $surveys = [
            [
                'title' => 'Encuesta de Satisfacción del Cliente',
                'description' => 'Ayúdanos a mejorar nuestros servicios',
                'status' => 'active',
            ],
            [
                'title' => 'Evaluación de Experiencia de Usuario',
                'description' => 'Queremos conocer tu experiencia con nuestra plataforma',
                'status' => 'active',
            ],
            [
                'title' => 'Encuesta de Producto',
                'description' => 'Cuéntanos qué piensas de nuestro nuevo producto',
                'status' => 'active',
            ],
            [
                'title' => 'Encuesta de Clima Laboral',
                'description' => 'Para empleados de la empresa',
                'status' => 'active',
            ],
            [
                'title' => 'Encuesta Antigua',
                'description' => 'Esta encuesta está desactivada',
                'status' => 'inactive',
            ],
        ];

        // Crear 2-3 encuestas por usuario
        foreach ($users as $user) {
            $numSurveys = rand(2, 3);

            for ($i = 0; $i < $numSurveys; $i++) {
                $surveyData = $surveys[array_rand($surveys)];

                Survey::create([
                    'title' => $surveyData['title'] . " - {$user->name}",
                    'description' => $surveyData['description'],
                    'status' => $surveyData['status'],
                    'created_by' => $user->id,
                ]);
            }
        }

    }
}
