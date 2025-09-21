<?php

use App\Models\User;

pest()->group('dashboard');

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertOk();
});
