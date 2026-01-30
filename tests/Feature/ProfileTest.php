<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

pest()->use(RefreshDatabase::class);

test('can get profile', function () {

    $user = \App\Domain\User\Models\User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/v1/auth/profile');

    $response->assertStatus(200)->assertJsonStructure(['data' => ['id', 'name', 'email', 'avatar']]);

});

test('can update profile', function () {
    $user = \App\Domain\User\Models\User::factory()->create();

    $response = $this->actingAs($user)->post('/api/v1/auth/profile', [
        'name' => 'test',
        'email' => 'test@test.com',
    ]);

    $response->assertStatus(200);
    \Pest\Laravel\assertDatabaseHas('users', ['id'=>$user->id, 'name'=>'test', 'email' => 'test@test.com']);
});


test('can upload avatar', function () {
    $user = \App\Domain\User\Models\User::factory()->create();
    $avatar = UploadedFile::fake()->image('test-avatar.jpg');

    $response = $this->actingAs($user)->postJson('/api/v1/auth/profile', [
        'avatar' => $avatar
    ]);
    $response->assertStatus(200);

    $response->assertStatus(200);
    $user->refresh();

    Storage::disk('public')->assertExists($user->avatar);
});
