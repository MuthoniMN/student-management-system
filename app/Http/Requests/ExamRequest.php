<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ExamDateValidation;

class ExamRequest extends FormRequest
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
            'semester_id' => 'required|integer|exists:semesters,id',
            'exam_date' => ['required', 'date', new ExamDateValidation($this->input('semester_id'))],
            'file' => 'nullable|file|max:2048|mimes:doc,docx,pdf',
            'grade_id' => 'required|integer|exists:grades,id',
        ];
    }
}
