<?php

namespace App\Domain\Rbac\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Permission extends \Spatie\Permission\Models\Permission
{
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => !empty($value) ? $this->nameChecks($value) : '',
        );
    }

    private function nameChecks($value)
    {
        if (request()?->route()?->named(['audits.index', 'audits.export'])) {
            return $this->display_name;
        }
        return $value;
    }

    public static function exists($permission): bool
    {
        return Permission::query()->where('name', '=', $permission)->count() > 0;
    }
}
