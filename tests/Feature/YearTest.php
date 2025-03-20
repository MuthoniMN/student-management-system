<?php

use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Grade;
use Inertia\Testing\AssertableInertia as Assert;

test('list years', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/years');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Year/List'));
});

test('display year form', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/years/create');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Year/Create'));
});

test('create a year', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/years', [
        'year' => '2027',
        'start_date' => fake()->date(),
        'end_date' => fake()->date(),
    ]);

    $response->assertStatus(302);
});

test('display year', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $year = AcademicYear::factory()->create();

    $response = $this->get("/years/{$year->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Year/Show'));
});

test('update year', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $year = AcademicYear::factory()->create();

    $response = $this->put("/years/{$year->id}", [
        'year' => '2030'
    ]);

    $response->assertStatus(302);
});

test('delete year', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $year = AcademicYear::factory()->create();

    $response = $this->delete("/years/{$year->id}");

    $response->assertStatus(302);
    $this->assertSoftDeleted($year);
});

test('year results', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $year = AcademicYear::factory()->create();
    $grade = Grade::factory()->create();

    $response = $this->get("years/{$year->id}/grades/{$grade->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Grade/Result'));
});
