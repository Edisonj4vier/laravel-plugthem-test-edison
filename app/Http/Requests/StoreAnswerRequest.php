<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validamos que 'answers' sea un array
            'answers' => 'required|array',

            // Validamos cada elemento dentro del array
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.value' => 'required|string',
        ];
    }
}
