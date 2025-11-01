<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SurveyController extends Controller
{
    use ApiResponseTrait;
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $surveys = $request->user()->surveys()->latest()->get();

        // Devolvemos una colección de Recursos
        return SurveyResource::collection($surveys);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyRequest $request)
    {
        $survey = $request->user()->surveys()->create($request->validated());
        return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey)
    {
        $survey->load('questions');
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $this->authorize('update', $survey);
        $survey->update($request->validated());
         return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
       $this->authorize('delete', $survey);
       $survey->delete();
        return $this->successResponse(null, 'Encuesta eliminada exitosamente.', 200);
    }
}
