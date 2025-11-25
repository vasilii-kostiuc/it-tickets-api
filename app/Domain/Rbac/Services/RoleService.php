<?php

namespace App\Domain\Rbac\Services;

use App\Domain\Rbac\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RoleService
{
    public function create(array $attributes, array $permissions = []): Model|Builder
    {
        /** @var \Spatie\Permission\Models\Role $role */
        $role = Role::create($attributes);
        $role->syncPermissions($permissions);

        return $role;
    }

    public function update(Role $role, array $attributes): bool
    {
        return $role->update($attributes);
    }

    public function remove(Role $role): bool
    {
        if ($role->users()->count() > 0) {
            throw new \Exception("The role cannot be deleted because there are users with this role");
        }

        return $role->delete();
    }

    public function updateRolePermissions(Role $role, $permissions): Role
    {
        return $role->syncPermissions($permissions);
    }
}
