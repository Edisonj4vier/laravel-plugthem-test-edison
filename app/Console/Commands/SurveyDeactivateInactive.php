<?php

namespace App\Console\Commands;

use App\Models\Survey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SurveyDeactivateInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:survey-deactivate-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desactiva encuestas activas que no han recibido respuestas en los últimos 30 días.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando encuestas inactivas...');

        $cutoffDate = now()->subDays(30);

        // Buscar todas las encuestas con más de 30 días sin nuevas respuestas.
        $surveysToDeactivate = Survey::where('status', 'active')
            // 1. Nos aseguramos que la encuesta tenga al menos 30 días de antigüedad
            ->where('created_at', '<=', $cutoffDate)
            // 2. Verificamos que no tenga respuestas 'recientes' (en los últimos 30 días)
            ->whereDoesntHave('answers', function ($query) use ($cutoffDate) {
                $query->where('created_at', '>=', $cutoffDate);
            })
            ->get();

            $count = $surveysToDeactivate->count();

            if ($count === 0) {
            $this->info('No se encontraron encuestas para desactivar.');
            return 0; // Salida exitosa
            }

            // Cambiar su estado a 'inactive'
            foreach ($surveysToDeactivate as $survey) {
            $survey->status = 'inactive';
            $survey->save();
            }

            //Registrar en el log
            $logMessage = "Comando 'survey:deactivate-inactive': Se desactivaron {$count} encuestas por inactividad.";
            Log::info($logMessage);

            $this->info("¡Éxito! Se desactivaron {$count} encuestas.");
            return 0; // Salida exitosa

    }
}
