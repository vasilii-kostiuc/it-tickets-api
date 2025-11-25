<?php

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Permission Index', function () {
    test('can list all permissions', function () {
        Permission::create(['name' => 'users.view', 'display_name' => 'View Users']);
        Permission::create(['name' => 'users.create', 'display_name' => 'Create Users']);
        Permission::create(['name' => 'users.edit', 'display_name' => 'Edit Users']);

        $response = $this->getJson('/api/v1/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'display_name', 'guard_name', 'created_at', 'updated_at']
                ],
                'meta',
                'links'
            ])
            ->assertJsonCount(3, 'data');
    });

});

describe('Permission Store', function () {
    test('can create a new permission', function () {
        $data = [
            'name' => 'users.delete',
            'display_name' => 'Delete Users',
        ];

        $response = $this->postJson('/api/v1/permissions', $data);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Permission created successfully')
            ->assertJsonPath('data.name', 'users.delete')
            ->assertJsonPath('data.display_name', 'Delete Users');

        $this->assertDatabaseHas('permissions', [
            'name' => 'users.delete',
            'display_name' => 'Delete Users'
        ]);
    });

    test('cannot create permission with duplicate name', function () {
        Permission::create(['name' => 'users.view']);

        $response = $this->postJson('/api/v1/permissions', [
            'name' => 'users.view',
            'display_name' => 'View Users'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    test('cannot create permission without name', function () {
        $response = $this->postJson('/api/v1/permissions', [
            'display_name' => 'Some Permission'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    test('permission name must be a string', function () {
        $response = $this->postJson('/api/v1/permissions', [
            'name' => 12345,
            'display_name' => 'Some Permission'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });
});

describe('Permission Show', function () {
    test('can view a single permission', function () {
        $permission = Permission::create([
            'name' => 'users.view',
            'display_name' => 'View Users'
        ]);

        $response = $this->getJson("/api/v1/permissions/{$permission->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $permission->id)
            ->assertJsonPath('data.name', 'users.view')
            ->assertJsonPath('data.display_name', 'View Users');
    });

    test('returns 404 for non-existent permission', function () {
        $response = $this->getJson('/api/v1/permissions/99999');

        $response->assertStatus(404);
    });
});

describe('Permission Update', function () {
    test('can update an existing permission', function () {
        $permission = Permission::create([
            'name' => 'users.view',
            'display_name' => 'View Users'
        ]);

        $response = $this->putJson("/api/v1/permissions/{$permission->id}", [
            'name' => 'users.view.all',
            'display_name' => 'View All Users'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Permission updated successfully')
            ->assertJsonPath('data.name', 'users.view.all')
            ->assertJsonPath('data.display_name', 'View All Users');

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => 'users.view.all',
            'display_name' => 'View All Users'
        ]);
    });

    test('cannot update permission with duplicate name', function () {
        $permission1 = Permission::create(['name' => 'users.view']);
        $permission2 = Permission::create(['name' => 'users.create']);

        $response = $this->putJson("/api/v1/permissions/{$permission2->id}", [
            'name' => 'users.view'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    test('can update only display name', function () {
        $permission = Permission::create([
            'name' => 'users.view',
            'display_name' => 'View Users'
        ]);

        $response = $this->putJson("/api/v1/permissions/{$permission->id}", [
            'name' => 'users.view',
            'display_name' => 'View All System Users'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.display_name', 'View All System Users');
    });
});

describe('Permission Destroy', function () {
    test('can delete a permission', function () {
        $permission = Permission::create(['name' => 'users.view']);

        $response = $this->deleteJson("/api/v1/permissions/{$permission->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Permission deleted successfully');

        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id
        ]);
    });

    test('returns 404 when deleting non-existent permission', function () {
        $response = $this->deleteJson('/api/v1/permissions/99999');

        $response->assertStatus(404);
    });

    test('permission is completely removed from database', function () {
        $permission = Permission::create(['name' => 'users.view']);
        $permissionId = $permission->id;

        $this->deleteJson("/api/v1/permissions/{$permissionId}");

        expect(Permission::find($permissionId))->toBeNull();
    });
});

describe('Permission Authorization', function () {
    test('unauthenticated user cannot access permissions', function () {
        auth()->logout();

        $response = $this->getJson('/api/v1/permissions');

        $response->assertStatus(401);
    });

    test('unauthenticated user cannot create permission', function () {
        auth()->logout();

        $response = $this->postJson('/api/v1/permissions', [
            'name' => 'users.view'
        ]);

        $response->assertStatus(401);
    });

    test('unauthenticated user cannot update permission', function () {
        $permission = Permission::create(['name' => 'users.view']);
        auth()->logout();

        $response = $this->putJson("/api/v1/permissions/{$permission->id}", [
            'name' => 'users.edit'
        ]);

        $response->assertStatus(401);
    });

    test('unauthenticated user cannot delete permission', function () {
        $permission = Permission::create(['name' => 'users.view']);
        auth()->logout();

        $response = $this->deleteJson("/api/v1/permissions/{$permission->id}");

        $response->assertStatus(401);
    });
});
