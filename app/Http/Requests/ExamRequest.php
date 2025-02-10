<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\ExamDateValidation;
use App\Models\Exam;

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
            'title' => [
                'required',
                'string',
                'max:255',
                function($attribute, $value, $fail){
                    $subject = $this->route('subject');
                    $sem = $this->input('semester_id');
                    $grade = $this->input('grade_id');

                    $saved = Exam::where('title', $value)->where('subject_id', $subject->id)->where('semester_id', $sem)->where('grade_id', $grade)->get();

                    if(count($saved) > 0){
                        $fail("This subject already has a $value");
                    }
                }
            ],
            'type' => [
                'required',
                'string',
                'max:255',
                Rule::in(['exam', 'CAT'])
            ],
            'semester_id' => 'required|integer|exists:semesters,id',
            'exam_date' => ['required', 'date', new ExamDateValidation($this->input('semester_id'))],
            'file' => 'nullable|file|max:2048|mimes:doc,docx,pdf',
            'grade_id' => 'required|integer|exists:grades,id',
        ];
    }
}
