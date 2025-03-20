<?php

use App\Models\User;
use App\Models\Result;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Exam;
use Inertia\Testing\AssertableInertia as Assert;

test('list results', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/results');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Result/Index'));
});

test('display result form for multiples', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/results/create');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Result/CreateMultiple'));
});

test('display result form for individual', function() {
    $user = User::factory()->create();
    $subject = Subject::factory()->create();
    $exam = Exam::factory()->create();
    $this->actingAs($user);

    $response = $this->get("/subjects/{$subject->id}/exams/{$exam->id}/results/create");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Result/Create'));
});

test('create a result', function() {
    $user = User::factory()->create();
    $exam = Exam::factory()->create();
    $subject = Subject::factory()->create();
    $student = Student::factory()->create();
    $this->actingAs($user);

    $response = $this->post("/subjects/{$subject->id}/exams/{$exam->id}/results", [
        'results' =>[
            'student_id' => $student->id,
            'grade' => 'A',
            'result' => 86
    ]]);

    $response->assertStatus(302);
});

test('update result', function() {
    $user = User::factory()->create();
    $subject = Subject::factory()->create();
    $exam = Exam::factory()->create();
    $this->actingAs($user);

    $result = Result::factory()->create();

    $response = $this->put("/subjects/{$subject->id}/exams/{$exam->id}/results/{$result->id}", [
        'title' => fake()->word()
    ]);

    $response->assertStatus(302);
});

test('delete result', function () {
    $user = User::factory()->create();
    $subject = Subject::factory()->create();
    $exam = Exam::factory()->create();
    $this->actingAs($user);

    $result = Result::factory()->create();

    $response = $this->delete("/subjects/{$subject->id}/exams/{$exam->id}/results/{$result->id}");

    $response->assertStatus(302);
    $this->assertSoftDeleted($result);
});

test('restore result', function() {
    $user = User::factory()->create();
    $subject = Subject::factory()->create();
    $exam = Exam::factory()->create();
    $this->actingAs($user);

    $result = Result::factory()->create();
    $result->deleted_at = now();
    $result->save();

    $response = $this->put("/subjects/{$subject->id}/exams/{$exam->id}/results/restore", [
        'id' => $result->id
    ]);

    $response->assertStatus(302);
    $this->assertNotSoftDeleted($result);
});

