<?php

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Создаем тестового пользователя для аутентификации
    $this->user = User::factory()->create();
});

describe('Users Tests', function () {

    it('can get list of users', function () {
        // Arrange
        User::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'email']
                ],
                'meta' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'errors',
            ])
            ->assertJson([
                'success' => true,
            ]);

        expect($response->json('data'))->toHaveCount(6); // 5 + 1 authenticated user
    });

    it('returns paginated results', function () {
        // Arrange
        User::factory()->count(20)->create();

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?per_page=10');

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.current_page', 1);

        expect($response->json('data'))->toHaveCount(10);
    });

    it('can navigate to second page', function () {
        // Arrange
        User::factory()->count(20)->create();

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?per_page=10&page=2');

        // Assert
        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 2);

        expect($response->json('data'))->toHaveCount(10);
    });

    it('can filter users by name (partial match)', function () {
        // Arrange
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
        User::factory()->create(['name' => 'Bob Johnson', 'email' => 'bob@example.com']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?filter[name]=John');

        // Assert
        $response->assertStatus(200);

        $users = collect($response->json('data'));
        expect($users)->toHaveCount(2); // John Doe + Bob Johnson
        expect($users->pluck('name')->every(fn($name) => str_contains($name, 'John')))->toBeTrue();
    });

    it('can filter users by email (partial match)', function () {
        // Arrange
        User::factory()->create(['name' => 'User 1', 'email' => 'test@gmail.com']);
        User::factory()->create(['name' => 'User 2', 'email' => 'another@gmail.com']);
        User::factory()->create(['name' => 'User 3', 'email' => 'different@yahoo.com']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?filter[email]=gmail');

        // Assert
        $response->assertStatus(200);

        $users = collect($response->json('data'));
        expect($users)->toHaveCount(2);
        expect($users->pluck('email')->every(fn($email) => str_contains($email, 'gmail')))->toBeTrue();
    });

    it('can filter users by exact id', function () {
        // Arrange
        $targetUser = User::factory()->create(['name' => 'Target User']);
        User::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($this->user)
            ->getJson("/api/v1/users?filter[id]={$targetUser->id}");

        // Assert
        $response->assertStatus(200);

        $users = collect($response->json('data'));
        expect($users)->toHaveCount(1);
        expect($users->first()['id'])->toBe($targetUser->id);
        expect($users->first()['name'])->toBe('Target User');
    });

    it('can search users by name or email', function () {
        // Arrange
        User::factory()->create(['name' => 'Search Term', 'email' => 'other@example.com']);
        User::factory()->create(['name' => 'Another User', 'email' => 'search.term@example.com']);
        User::factory()->create(['name' => 'Random User', 'email' => 'random@example.com']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?filter[search]=search');

        // Assert
        $response->assertStatus(200);

        $users = collect($response->json('data'));
        expect($users)->toHaveCount(2);
    });

    it('can sort users by name ascending', function () {
        // Arrange
        User::factory()->create(['name' => 'Charlie']);
        User::factory()->create(['name' => 'Alice']);
        User::factory()->create(['name' => 'Bob']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?sort=name');

        // Assert
        $response->assertStatus(200);

        $names = collect($response->json('data'))->pluck('name')->toArray();
        $sortedNames = collect($names)->sort()->values()->toArray();

        expect($names)->toBe($sortedNames);
    });

    it('can sort users by name descending', function () {
        // Arrange
        User::factory()->create(['name' => 'Charlie']);
        User::factory()->create(['name' => 'Alice']);
        User::factory()->create(['name' => 'Bob']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?sort=-name');

        // Assert
        $response->assertStatus(200);

        $names = collect($response->json('data'))->pluck('name')->toArray();
        $sortedNames = collect($names)->sortDesc()->values()->toArray();

        expect($names)->toBe($sortedNames);
    });

    it('can sort users by created_at descending (default)', function () {
        // Arrange
        $oldUser = User::factory()->create(['created_at' => now()->subDays(3)]);
        $newUser = User::factory()->create(['created_at' => now()->addDays(1)]);
        $midUser = User::factory()->create(['created_at' => now()->subDays(1)]);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users');

        // Assert
        $response->assertStatus(200);

        $ids = collect($response->json('data'))->pluck('id')->toArray();
        // Первый должен быть самый новый
        expect($ids[0])->toBe($newUser->id);
    });

    it('can sort users by email', function () {
        // Arrange
        User::factory()->create(['email' => 'c@example.com']);
        User::factory()->create(['email' => 'a@example.com']);
        User::factory()->create(['email' => 'b@example.com']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?sort=email');

        // Assert
        $response->assertStatus(200);

        $emails = collect($response->json('data'))->pluck('email')->toArray();
        $sortedEmails = collect($emails)->sort()->values()->toArray();

        expect($emails)->toBe($sortedEmails);
    });

    it('can combine filters and sorting', function () {
        // Arrange
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@gmail.com']);
        User::factory()->create(['name' => 'John Smith', 'email' => 'smith@gmail.com']);
        User::factory()->create(['name' => 'Jane Doe', 'email' => 'jane@yahoo.com']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?filter[email]=gmail&sort=name');

        // Assert
        $response->assertStatus(200);

        $users = collect($response->json('data'));
        expect($users)->toHaveCount(2);
        expect($users->first()['name'])->toBe('John Doe');
        expect($users->last()['name'])->toBe('John Smith');
    });

    it('can combine multiple filters', function () {
        // Arrange
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@gmail.com']);
        User::factory()->create(['name' => 'John Smith', 'email' => 'john@yahoo.com']);
        User::factory()->create(['name' => 'Jane Doe', 'email' => 'jane@gmail.com']);

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?filter[name]=John&filter[email]=gmail');

        // Assert
        $response->assertStatus(200);

        $users = collect($response->json('data'));
        expect($users)->toHaveCount(1);
        expect($users->first()['name'])->toBe('John Doe');
        expect($users->first()['email'])->toBe('john@gmail.com');
    });

    it('returns empty data when no users match filter', function () {
        // Arrange
        User::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/users?filter[name]=NonExistentName');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    });

    it('requires authentication', function () {
        // Act
        $response = $this->getJson('/api/v1/users');

        // Assert
        $response->assertStatus(401);
    });

});
