<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Semester;

class ExamDateValidation implements ValidationRule
{
    protected $semester;

    public function __construct($semester_id){
        $this->semester = Semester::find($semester_id);
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(
            ($value > $this->semester->start_date)
            &&
            ($value <= $this->semester->end_date)
        ){
            $fail('The :attribute should be within the semester dates.');
        }
    }
}
