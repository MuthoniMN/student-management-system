<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxSemestersPerYear;
use App\Models\Semester;

class SemesterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
                function ($attribute, $value, $fail) {
                    $semester = $this->route('semester');
                    if($semester && $semester->academic_year_id == $value){
                            return;
                    }
                    $semesterCount = Semester::where('academic_year_id', $value)->count();
                    if ($semesterCount >= 2) {
                        $fail('An academic year cannot have more than 2 semesters.');
                    }
                },
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                function($attribute, $value, $fail){
                    $year = $this->input('academic_year_id');
                    $semester = $this->route('semester');

                    if($semester && $semester->title == $value){
                        return;
                    }

                    $saved = Semester::where('title', $value)->where('academic_year_id', $year)->get();

                    if(count($saved) > 0) {
                        $fail("This academic year already has $value");
                    }
                }
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];
    }
}
