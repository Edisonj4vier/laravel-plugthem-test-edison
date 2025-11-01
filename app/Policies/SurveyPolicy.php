<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SurveyPolicy
{

     /**
     * Determine if the user can view the survey.
     */
    public function view(User $user, Survey $survey): bool
    {
        // Cualquiera puede ver encuestas activas,
        // pero solo el creador puede ver sus propias inactivas
        return $survey->status === 'active' || $user->id === $survey->created_by;
    }

    /**
     * Determine whether the user can update the model.
     * Solo el creador puede actualizar.
     */
    public function update(User $user, Survey $survey): bool
    {
        return $user->id === $survey->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     * Solo el creador puede eliminar.
     */
    public function delete(User $user, Survey $survey): bool
    {
        return $user->id === $survey->created_by;
    }
}
