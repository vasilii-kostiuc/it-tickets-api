<?php

namespace App\Domain\Rbac\Services;

use App\Domain\Rbac\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PermissionService
{
    public function create(array $data): Builder|Model
    {
        return Permission::create($data);
    }

    public function update(Permission $permission, array $data): bool
    {
        return $permission->update($data);
    }

    public function remove(Permission $permission): bool
    {
        return $permission->delete();
    }
}
