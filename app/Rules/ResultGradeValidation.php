<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Exam;
use App\Models\Student;

class ResultGradeValidation implements ValidationRule
{
    protected $exam, $student;

    public function __construct($examId, $studentId){
        $this->exam = Exam::find($examId);
        $this->student = Student::find($studentId);
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->exam->grade_id != $this->student->grade_id){
            $fail('The student isn\'t currently enrolled in this exam\'s grade');
        }
    }
}
