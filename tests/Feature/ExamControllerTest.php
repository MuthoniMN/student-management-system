<?php

use App\Models\Subject;
use App\Models\Exam;
use App\Models\User;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\AcademicYear;
use Inertia\Testing\AssertableInertia as Assert;

test('successfully view the subject exams', function () {
    $subject = Subject::factory()->make();
    $user = User::factory()->make();

    $response = $this->actingAs($user)->get("/subjects/{$subject->id}");

    $response->assertStatus(200);
});

test('successfully creates an exam without the document', function() {
    $startDate = new DateTime('2026-1-07');
    $endDate = new DateTime('2026-5-31');
    $randomTimestamp = mt_rand($startDate->getTimestamp(), $endDate->getTimestamp());
    $dateBetween = (new DateTime())->setTimestamp($randomTimestamp);

    $subject = Subject::factory()->create();
    $semester = Semester::factory()->create([
        'start_date' => $startDate,
        'end_date' => $endDate
        ]);
    $grade = Grade::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post("/subjects/{$subject->id}/exams", [
        'title' => "CAT 1",
        'type' => 'CAT',
        'semester_id' => $semester->id,
        'exam_date' => $dateBetween->format('Y-m-d'),
        'grade_id' => $grade->id
    ]);


    $response->assertStatus(302);
});

test('exam page returns 200', function(){
    $exam = Exam::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get("/subjects/{$exam->subject->id}/exams/{$exam->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Exam/Show')->has('exam'));
});

test('update exam', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $exam = Exam::factory()->create();

    $response = $this->put("/subjects/{$exam->subject->id}/exams/{$exam->id}",[
        'title' => 'CAT 3'
    ]);

    $response->assertStatus(302);
});

test('soft deleting exam', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $exam = Exam::factory()->create();

    $response = $this->delete("/subjects/{$exam->subject->id}/exams/{$exam->id}");

    $response->assertStatus(302);

    $this->assertSoftDeleted($exam);
});

test('restoring soft deleted exam', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $exam = Exam::factory()->create();
    $subject = $exam->subject;
    $exam->deleted_at = now();

    $response = $this->put("subjects/{$subject->id}/exams/restore", [
        'id' => $exam->id
    ]);

    $response->assertStatus(302);

    $this->assertNotSoftDeleted($exam);
});
