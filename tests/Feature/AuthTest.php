<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('auth register', function () {

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Valid name',
        'email' => 'valid@email.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    \Pest\Laravel\assertDatabaseHas('users', ['email' => 'valid@email.com', 'name' => 'Valid name']);
    $response->assertStatus(200)->assertJsonStructure(['data' => ['token', 'user']]);
});


test('auth login', function () {
    $userService = app(\App\Domain\User\Services\UserService::class);

    $user = $userService->register('valid@email.com', '12345678', 'Valid name');

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'valid@email.com',
        'password' => '12345678',
    ]);

    $response->assertStatus(200)->assertJsonStructure(['data' => ['token', 'user']]);

});
