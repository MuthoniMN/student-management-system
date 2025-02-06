<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ExamUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => [
                'required',
                'string',
                'max:255',
                Rule::in(['exam', 'CAT'])
            ],
            'grade_id' => 'required|exists:grades,id',
            'semester_id' => 'required|exists:semesters,id'
        ];
    }
}
