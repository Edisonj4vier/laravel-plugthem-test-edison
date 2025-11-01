<?php

namespace App\Http\Controllers;

use App\Events\SurveyAnswered;
use App\Http\Requests\StoreAnswerRequest;
use App\Models\Answer;
use App\Models\Survey;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
     use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnswerRequest $request, Survey $survey)
    {
        //Un usuario autenticado puede responder una encuesta activa.
         if ($survey->status !== 'active') {
            return $this->errorResponse('Esta encuesta no está activa.', 403);
        }

        $validated = $request->validated();
        $user = $request->user();

        try {
            DB::beginTransaction();

            foreach ($validated['answers'] as $answerData) {
                Answer::create([
                    'user_id' => $user->id,
                    'survey_id' => $survey->id,
                    'question_id' => $answerData['question_id'],
                    'value' => $answerData['value'],
                ]);
            }

            // Si todo salió bien, confirmamos los cambios
            DB::commit();

        } catch (QueryException $e) {
            // Si algo falla (ej: el usuario ya respondió - unique constraint),
            // revertimos todos los cambios.
            DB::rollBack();

            // Verificamos si el error es por la restricción única (ya respondió)
            if ($e->errorInfo[1] == 1062) { // 1062 = Error de entrada duplicada
                return $this->errorResponse('Ya has respondido a una de estas preguntas.', 409); // 409 Conflict
            }

            // Otro error de base de datos
            return $this->errorResponse('Error al guardar las respuestas.', 500);
        }

        // Disparar el evento DESPUÉS de que la transacción fue exitosa.
        SurveyAnswered::dispatch($survey, $user);

        return $this->successResponse(null, 'Respuestas guardadas exitosamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
