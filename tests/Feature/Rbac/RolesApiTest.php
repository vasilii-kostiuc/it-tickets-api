<?php

use App\Domain\Rbac\Models\Role;
use App\Domain\Rbac\Models\Permission;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Role API', function () {

    describe('index', function () {
        it('can list all roles', function () {
            Role::factory()->count(15)->create();

            $response = $this->getJson('/api/v1/roles');

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'description', 'created_at']
                    ],
                    'meta',
                    'links'
                ]);
        });

        it('can paginate roles', function () {
            Role::factory()->count(15)->create();

            $response = $this->getJson('/api/v1/roles?per_page=5');

            $response->assertStatus(200)
                ->assertJsonPath('meta.per_page', 5)
                ->assertJsonCount(5, 'data');
        });

        it('can filter roles by name', function () {
            Role::factory()->create(['name' => 'Admin']);
            Role::factory()->create(['name' => 'User']);
            Role::factory()->create(['name' => 'Manager']);

            $response = $this->getJson('/api/v1/roles?filter[name]=Admin');

            $response->assertStatus(200)
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.name', 'Admin');
        });

        it('can filter roles by description', function () {
            Role::factory()->create(['description' => 'Administrator role']);
            Role::factory()->create(['description' => 'Regular user role']);

            $response = $this->getJson('/api/v1/roles?filter[description]=Administrator');

            $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
        });

        it('can search roles by name or description', function () {
            Role::factory()->create(['name' => 'Admin', 'description' => 'Admin role']);
            Role::factory()->create(['name' => 'User', 'description' => 'User role']);

            $response = $this->getJson('/api/v1/roles?filter[search]=Admin');

            $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
        });

        it('can sort roles by name', function () {
            Role::factory()->create(['name' => 'Zebra']);
            Role::factory()->create(['name' => 'Alpha']);
            Role::factory()->create(['name' => 'Beta']);

            $response = $this->getJson('/api/v1/roles?sort=name');

            $response->assertStatus(200);
            $data = $response->json('data');
            expect($data[0]['name'])->toBe('Alpha')
                ->and($data[1]['name'])->toBe('Beta')
                ->and($data[2]['name'])->toBe('Zebra');
        });

        it('can sort roles descending', function () {
            Role::factory()->create(['name' => 'Alpha']);
            Role::factory()->create(['name' => 'Beta']);
            Role::factory()->create(['name' => 'Zebra']);

            $response = $this->getJson('/api/v1/roles?sort=-name');

            $response->assertStatus(200);
            $data = $response->json('data');
            expect($data[0]['name'])->toBe('Zebra')
                ->and($data[2]['name'])->toBe('Alpha');
        });
    });

    describe('store', function () {
        it('can create a new role', function () {
            $roleData = [
                'name' => 'New Role',
                'description' => 'New role description'
            ];

            $response = $this->postJson('/api/v1/roles', $roleData);

            $response->assertStatus(200)
                ->assertJsonPath('data.name', 'New Role')
                ->assertJsonPath('data.description', 'New role description');

            $this->assertDatabaseHas('roles', $roleData);
        });

        it('can create a role with permissions', function () {
            $permissions = Permission::factory()->count(3)->create();

            $roleData = [
                'name' => 'Role with Permissions',
                'description' => 'Test role',
                'permissions' => $permissions->pluck('id')->toArray()
            ];

            $response = $this->postJson('/api/v1/roles', $roleData);

            $response->assertStatus(200);

            $role = Role::where('name', 'Role with Permissions')->first();
            expect($role->permissions)->toHaveCount(3);
        });

        it('requires name field', function () {
            $response = $this->postJson('/api/v1/roles', [
                'description' => 'Test description'
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        });

        it('requires unique name', function () {
            Role::factory()->create(['name' => 'Existing Role']);

            $response = $this->postJson('/api/v1/roles', [
                'name' => 'Existing Role',
                'description' => 'Test'
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        });
    });

    describe('show', function () {
        it('can show a single role', function () {
            $role = Role::factory()->create();

            $response = $this->getJson("/api/v1/roles/{$role->id}");

            $response->assertStatus(200)
                ->assertJsonPath('data.id', $role->id)
                ->assertJsonPath('data.name', $role->name);
        });

        it('returns 404 for non-existent role', function () {
            $response = $this->getJson('/api/v1/roles/99999');

            $response->assertStatus(404);
        });
    });

    describe('update', function () {
        it('can update a role', function () {
            $role = Role::factory()->create([
                'name' => 'Old Name',
                'description' => 'Old description'
            ]);

            $updateData = [
                'name' => 'New Name',
                'description' => 'New description'
            ];

            $response = $this->putJson("/api/v1/roles/{$role->id}", $updateData);

            $response->assertStatus(200)
                ->assertJsonPath('data.name', 'New Name')
                ->assertJsonPath('data.description', 'New description');

            $this->assertDatabaseHas('roles', $updateData);
        });

        it('can update role name only', function () {
            $role = Role::factory()->create(['name' => 'Old Name']);

            $response = $this->putJson("/api/v1/roles/{$role->id}", [
                'name' => 'Updated Name'
            ]);

            $response->assertStatus(200)
                ->assertJsonPath('data.name', 'Updated Name');
        });

        it('prevents duplicate names when updating', function () {
            Role::factory()->create(['name' => 'Existing Role']);
            $role = Role::factory()->create(['name' => 'My Role']);

            $response = $this->putJson("/api/v1/roles/{$role->id}", [
                'name' => 'Existing Role'
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        });
    });

    describe('destroy', function () {
        it('can delete a role', function () {
            $role = Role::factory()->create();

            $response = $this->deleteJson("/api/v1/roles/{$role->id}");

            $response->assertStatus(200);
            $this->assertDatabaseMissing('roles', ['id' => $role->id]);
        });

        it('returns 404 when deleting non-existent role', function () {
            $response = $this->deleteJson('/api/v1/roles/99999');

            $response->assertStatus(404);
        });
    });

    describe('updatePermissions', function () {
        it('can update role permissions', function () {
            $role = Role::factory()->create();
            $oldPermissions = Permission::factory()->count(2)->create();
            $role->permissions()->attach($oldPermissions->pluck('id'));

            $newPermissions = Permission::factory()->count(3)->create();

            $response = $this->putJson("/api/v1/roles/{$role->id}/permissions", [
                'permissions' => $newPermissions->pluck('id')->toArray()
            ]);

            $response->assertStatus(200);

            $role->refresh();
            expect($role->permissions)->toHaveCount(3);
            expect($role->permissions->pluck('id')->toArray())
                ->toMatchArray($newPermissions->pluck('id')->toArray());
        });

        it('can remove all permissions from role', function () {
            $role = Role::factory()->create();
            $permissions = Permission::factory()->count(3)->create();
            $role->permissions()->attach($permissions->pluck('id'));

            $response = $this->putJson("/api/v1/roles/{$role->id}/permissions", [
                'permissions' => []
            ]);

            $response->assertStatus(200);

            $role->refresh();
            expect($role->permissions)->toHaveCount(0);
        });

        it('validates permission ids are integers', function () {
            $role = Role::factory()->create();

            $response = $this->putJson("/api/v1/roles/{$role->id}/permissions", [
                'permissions' => ['invalid', 'ids']
            ]);

            $response->assertStatus(422);
        });

        it('validates permissions exist', function () {
            $role = Role::factory()->create();

            $response = $this->putJson("/api/v1/roles/{$role->id}/permissions", [
                'permissions' => [99999, 88888]
            ]);

            $response->assertStatus(422);
        });
    });
});
