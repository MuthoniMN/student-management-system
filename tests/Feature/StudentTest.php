<?php

use App\Models\User;
use App\Models\Student;
use App\Models\Semester;
use App\Models\AcademicYear;
use Inertia\Testing\AssertableInertia as Assert;

test('list students', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/students');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Student/List'));
});

test('display student form', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/students/create');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Student/Create'));
});

test('create a student', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/students', [
        'title' => 'Art',
        'description' => fake()->sentence(),
    ]);

    $response->assertStatus(302);
});

test('display student', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $student = Student::factory()->create();

    $response = $this->get("/students/{$student->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Student/Show'));
});

test('update student', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $student = Student::factory()->create();

    $response = $this->put("/students/{$student->id}", [
        'title' => fake()->word()
    ]);

    $response->assertStatus(302);
});

test('delete student', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $student = Student::factory()->create();

    $response = $this->delete("/students/{$student->id}");

    $response->assertStatus(302);
    $this->assertSoftDeleted($student);
});

test('year results', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $year = AcademicYear::factory()->create();
    $student = Student::factory()->create();

    $response = $this->get("students/{$student->id}/years/{$year->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Student/YearlyResults'));
});

