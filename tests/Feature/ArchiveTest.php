<?php

use App\Models\User;

test('successfully redirects unauthorized users', function(){
    $response = $this->get('/archive');

    $response->assertRedirectToRoute('login');
});

test('successfuly fetch the archive page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/archive');

    $response->assertStatus(200);
});

test('successfully fetch semester archive', function() {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/archive/semesters');

    $response->assertStatus(200);
});

test('successfully fetch subject archive', function() {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/archive/subjects');

    $response->assertStatus(200);
});

test('successfully fetch student archive', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/archive/students');

    $response->assertStatus(200);
});

test('successfully fetch exam archive', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/archive/exams');

    $response->assertStatus(200);
});

test('successfully fetch result archive', function() {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/archive/results');

    $response->assertStatus(200);
});
