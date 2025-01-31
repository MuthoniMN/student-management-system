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
                    $semesterCount = Semester::where('academic_year_id', $value)->count();
                    if ($semesterCount >= 2) {
                        $fail('An academic year cannot have more than 2 semesters.');
                    }
                },
            ],
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];
    }
}
