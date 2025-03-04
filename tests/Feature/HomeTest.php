<?php

use App\Models\User;

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertRedirectToRoute('login');
});

it('returns the students index page for authenticated users', function() {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertRedirectToRoute('students.index');
});
