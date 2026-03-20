<?php

use App\Domain\Ticket\Models\Sla;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('SLA API', function () {

    it('can list slas', function () {
        Sla::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/slas');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['*' => ['id', 'name', 'duration', 'grace_duration', 'description']],
                'meta',
                'links',
                'errors',
            ])
            ->assertJson(['success' => true]);

        expect($response->json('data'))->toHaveCount(3);
    });

    it('can create a sla', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/slas', [
                'name'           => 'Standard SLA',
                'duration'       => 480,
                'grace_duration' => 30,
                'description'    => 'Standard response SLA',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Standard SLA')
            ->assertJsonPath('data.duration', 480);

        $this->assertDatabaseHas('slas', ['name' => 'Standard SLA']);
    });

    it('can show a sla', function () {
        $sla = Sla::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/v1/slas/{$sla->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $sla->id);
    });

    it('can update a sla', function () {
        $sla = Sla::factory()->create();

        $response = $this->actingAs($this->user)
            ->putJson("/api/v1/slas/{$sla->id}", [
                'name'     => 'Updated SLA',
                'duration' => 240,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated SLA')
            ->assertJsonPath('data.duration', 240);

        $this->assertDatabaseHas('slas', ['id' => $sla->id, 'duration' => 240]);
    });

    it('can delete a sla', function () {
        $sla = Sla::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/slas/{$sla->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('slas', ['id' => $sla->id]);
    });

    it('returns 422 when creating without required fields', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/slas', []);

        $response->assertStatus(422);
    });

    it('cannot create sla with duplicate name', function () {
        Sla::factory()->create(['name' => 'Unique SLA']);

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/slas', [
                'name'     => 'Unique SLA',
                'duration' => 120,
            ]);

        $response->assertStatus(422);
    });

    it('requires authentication', function () {
        $response = $this->getJson('/api/v1/slas');

        $response->assertStatus(401);
    });
});
