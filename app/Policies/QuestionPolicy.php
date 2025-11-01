<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Survey $survey): bool
    {
        return $user->id === $survey->created_by;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Question $question): bool
    {
        // Verificamos si el usuario es el creador de la encuesta asociada
        return $user->id === $question->survey->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Question $question): bool
    {
        return $this->update($user, $question);
    }

}
