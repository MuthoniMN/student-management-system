<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ResultGradeValidation;
use App\Models\Result;

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
                new ResultGradeValidation($this->route('exam')->id, $this->input('student_id')),
                function ($attribute, $value, $fail){
                    $student = $this->input('student_id');
                    $exam = $this->route('exam');
                    $saved = Result::where('student_id', $student)->where('exam_id', $exam->id)->get();
                    if(count($saved) > 0){
                        $fail('This student already has results for this exam');
                    }
                }
            ]
        ];
    }
}
