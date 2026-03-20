<?php

use App\Domain\Department\Models\Department;
use App\Domain\Ticket\Models\Sla;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Department API', function () {

    it('can list departments', function () {
        Department::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/departments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['*' => ['id', 'name', 'ext_mask', 'queue1', 'queue2', 'manager_id', 'sla_id']],
                'meta',
                'links',
                'errors',
            ])
            ->assertJson(['success' => true]);

        expect($response->json('data'))->toHaveCount(3);
    });

    it('can create a department', function () {
        $manager = User::factory()->create();

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/departments', [
                'name'       => 'IT Support',
                'ext_mask'   => '1XX',
                'queue1'     => 'queue-001',
                'queue2'     => 'queue-002',
                'manager_id' => $manager->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'IT Support')
            ->assertJsonPath('data.sla_id', null);

        $this->assertDatabaseHas('departments', ['name' => 'IT Support']);
    });

    it('can create a department with sla', function () {
        $manager = User::factory()->create();
        $sla = Sla::factory()->create();

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/departments', [
                'name'       => 'HR',
                'ext_mask'   => '2XX',
                'queue1'     => 'queue-010',
                'queue2'     => 'queue-011',
                'manager_id' => $manager->id,
                'sla_id'     => $sla->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.sla_id', $sla->id);
    });

    it('can show a department', function () {
        $department = Department::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/v1/departments/{$department->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $department->id);
    });

    it('can update a department', function () {
        $department = Department::factory()->create();

        $response = $this->actingAs($this->user)
            ->putJson("/api/v1/departments/{$department->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name');

        $this->assertDatabaseHas('departments', ['id' => $department->id, 'name' => 'Updated Name']);
    });

    it('can delete a department', function () {
        $department = Department::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/departments/{$department->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    });

    it('returns 422 when creating without required fields', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/departments', []);

        $response->assertStatus(422);
    });

    it('can add a user to a department', function () {
        $department = Department::factory()->create();
        $member = User::factory()->create();

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/departments/{$department->id}/users", [
                'user_id' => $member->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('department_user', [
            'department_id' => $department->id,
            'user_id'       => $member->id,
        ]);
    });

    it('can remove a user from a department', function () {
        $department = Department::factory()->create();
        $member = User::factory()->create();
        $department->users()->attach($member->id);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/departments/{$department->id}/users/{$member->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('department_user', [
            'department_id' => $department->id,
            'user_id'       => $member->id,
        ]);
    });

    it('requires authentication', function () {
        $response = $this->getJson('/api/v1/departments');

        $response->assertStatus(401);
    });
});
