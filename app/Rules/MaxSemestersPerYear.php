<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxSemestersPerYear implements ValidationRule
{
    public function __construct($yearId){
        $this->yearId = $yearId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $semesterCount = Semester::where('academic_year_id', $this->yearId)->count();
        return $semesterCount < 2;
    }

    public function message(){
        return 'An academic year cannot have more than 2 semesters';
    }
}
