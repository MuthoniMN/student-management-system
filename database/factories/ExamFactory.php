<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Semester;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'type' => $this->faker->randomElement(['CAT', 'exam']),
            'exam_date' => $this->faker->date(),
            'subject_id' => Subject::factory()->create(),
            'grade_id' => Grade::factory()->create(),
            'semester_id' => Semester::factory()->create()
        ];
    }
}
