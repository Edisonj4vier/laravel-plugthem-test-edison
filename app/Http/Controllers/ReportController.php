<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Services\ReportService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ApiResponseTrait;

     public function __construct(protected ReportService $reportService)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey)
    {
        // 3. Usar el servicio para obtener los datos
        $reportData = $this->reportService->generateSurveyReport($survey);

        return $this->successResponse($reportData, 'Reporte generado exitosamente');
    }
}
