<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ResultGradeValidation;

class ResultRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'result' => 'required|integer|between:0,100',
            'grade' => 'required|string',
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
                new ResultGradeValidation($this->route('exam')->id, $this->input('student_id'))
            ]
        ];
    }
}
