<?php

use App\Models\User;
use App\Models\Subject;
use Inertia\Testing\AssertableInertia as Assert;

test('list subjects', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/subjects');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Subject/Index'));
});

test('display subject form', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/subjects/create');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Subject/Create'));
});

test('create a subject', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/subjects', [
        'title' => 'Art',
        'description' => fake()->sentence(),
    ]);

    $response->assertStatus(302);
});

test('display subject', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $subject = Subject::factory()->create();

    $response = $this->get("/subjects/{$subject->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Subject/Show'));
});

test('update subject', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $subject = Subject::factory()->create();

    $response = $this->put("/subjects/{$subject->id}", [
        'title' => fake()->word()
    ]);

    $response->assertStatus(302);
});

test('delete subject', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $subject = Subject::factory()->create();

    $response = $this->delete("/subjects/{$subject->id}");

    $response->assertStatus(302);
    $this->assertSoftDeleted($subject);
});

test('restore subject', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    $subject = Subject::factory()->create();
    $subject->deleted_at = now();
    $subject->save();

    $response = $this->put("/subjects/restore", [
        'id' => $subject->id
    ]);

    $response->assertStatus(302);
    $this->assertNotSoftDeleted($subject);
});

