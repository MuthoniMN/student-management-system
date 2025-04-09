<?php

use App\Models\User;
use App\Models\Grade;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('create grades', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/grades', [
        'name' => 'Grade 10',
        'description' => 'This is for grade 10 learners'
    ]);

    $response->assertRedirectToRoute('grades.index');
    $response->assertStatus(302);

    $this->assertDatabaseHas('grades', [
        'name' => 'Grade 10',
    ]);
});

test('only admins have access', function(){
    $user = User::factory()->create([ 'role' => 'student' ]);

    $response = $this->actingAs($user)->get('/grades');

    $response->assertStatus(403);
});

test('list grades', function(){
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/grades');

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Grade/List')->has('grades'));
});

test('edit grade page', function(){
    $user = User::factory()->create();
    $grade = Grade::factory()->create();
    $response = $this->actingAs($user)->get("/grades/{$grade->id}/edit");

    $response->assertStatus(200);
    $response->assertInertia(fn(Assert $page) => $page->component('Grade/Edit')->has('grade'));
});

test('edit grade', function(){
    $user = User::factory()->create();
    $grade = Grade::factory()->create();
    $response = $this->actingAs($user)->put("/grades/{$grade->id}", [
        'name' => 'Grade 10',
        'description' => 'grade 10 learners'
    ]);

    $response->assertRedirectToRoute('grades.index');
    $response->assertStatus(302);
});

