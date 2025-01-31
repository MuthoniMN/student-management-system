<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YearUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => 'required|string|exists:academic_years',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];
    }
}
