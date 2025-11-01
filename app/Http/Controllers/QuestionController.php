<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Survey;
use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use ApiResponseTrait;
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Survey $survey)
    {
        return QuestionResource::collection($survey->questions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request, Survey $survey)
    {
        $this->authorize('create', [Question::class, $survey]);

        $question = $survey->questions()->create($request->validated());

        return $this->successResponse(
            new QuestionResource($question),
            'Pregunta creada exitosamente',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $this->authorize('update', $question);

        $question->update($request->validated());

        return $this->successResponse(
            new QuestionResource($question),
            'Pregunta actualizada exitosamente'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);

        $question->delete();

        return $this->successResponse(null, 'Pregunta eliminada');
    }
}
