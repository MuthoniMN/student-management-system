<?php

use App\Models\User;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Grade;
use Inertia\Testing\AssertableInertia as Assert;

test('semester list', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/semesters/');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Semester/List')->has('semesters'));
});

test('display semester form', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/semesters/create');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Semester/Create')->has('years'));
});

test('create a new semester', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $year = AcademicYear::factory()->create();

    $response = $this->post('/semesters', [
        'title' => 'Semester 1',
        'start_date' => fake()->date(),
        'end_date' => fake()->date(),
        'academic_year_id' => $year->id
    ]);

    $response->assertStatus(302);
});

test('view semester', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $semester = Semester::factory()->create();

    $response = $this->get("/semesters/{$semester->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Semester/Show'));
});

test('view edit form', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $semester = Semester::factory()->create();

    $response = $this->get("/semesters/{$semester->id}/edit");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Semester/Edit')->has('semester'));
});

test('update semester', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $semester = Semester::factory()->create();

    $response = $this->put("/semesters/{$semester->id}", [
        'title' => 'Semester 2'
    ]);

    $response->assertStatus(302);
});

test('delete semester', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $semester = Semester::factory()->create();

    $response = $this->delete("/semesters/{$semester->id}");

    $response->assertStatus(302);
    $this->assertSoftDeleted($semester);
});

test('restore semester', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $semester = Semester::factory()->create();
    $semester->deleted_at = now();
    $semester->save();

    $response = $this->put("/semesters/restore", [
        'id' => $semester->id
    ]);

    $response->assertStatus(302);
    $this->assertNotSoftDeleted($semester);
});

test('semester results', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $semester = Semester::factory()->create();
    $grade = Grade::factory()->create();

    $response = $this->get("semesters/{$semester->id}/grades/{$grade->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Grade/Result'));
});
