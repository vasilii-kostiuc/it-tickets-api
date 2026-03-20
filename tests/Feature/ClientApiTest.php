<?php

use App\Domain\Client\Models\Client;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Client API', function () {

    it('can list clients', function () {
        Client::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/clients');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['*' => ['id', 'name', 'email', 'phone', 'phone1', 'phone2']],
                'meta',
                'links',
                'errors',
            ])
            ->assertJson(['success' => true]);

        expect($response->json('data'))->toHaveCount(3);
    });

    it('can create a client', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/clients', [
                'name'  => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+79001234567',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.phone', '+79001234567');

        $this->assertDatabaseHas('clients', ['phone' => '+79001234567']);
    });

    it('can show a client', function () {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/v1/clients/{$client->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $client->id);
    });

    it('can update a client', function () {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)
            ->putJson("/api/v1/clients/{$client->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name');

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Updated Name']);
    });

    it('can delete a client', function () {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/clients/{$client->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    });

    it('returns 422 when creating without required fields', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/clients', []);

        $response->assertStatus(422);
    });

    it('cannot create client with duplicate phone', function () {
        Client::factory()->create(['phone' => '+79001234567']);

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/clients', [
                'name'  => 'Another',
                'phone' => '+79001234567',
            ]);

        $response->assertStatus(422);
    });

    it('requires authentication', function () {
        $response = $this->getJson('/api/v1/clients');

        $response->assertStatus(401);
    });
});
