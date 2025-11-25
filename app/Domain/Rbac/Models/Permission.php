<?php

namespace App\Domain\Rbac\Models;

use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

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

    public static function newFactory(): PermissionFactory
    {
        return new PermissionFactory();
    }
}
