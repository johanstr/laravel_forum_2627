<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('regular users are redirected to the landing page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertRedirect('/');
});

test('admins can view the dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertOk();
});

test('regular users end up on the landing page after login', function () {
    $user = User::factory()->create();

    $response = $this->followingRedirects()->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertViewIs('thread.index');
});

test('new users are not an admin by default', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($user->isAdmin())->toBeFalse();
});

test('only admins see the dashboard link', function (string $url) {
    $user = User::factory()->create();
    $admin = User::factory()->admin()->create();

    $this->actingAs($user)->get($url)->assertDontSee(route('dashboard'));
    $this->actingAs($admin)->get($url)->assertSee(route('dashboard'));
})->with(['/', '/profile']);

test('landing page contains a logout form for users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertSee('action="'.route('logout').'"', false);
});
