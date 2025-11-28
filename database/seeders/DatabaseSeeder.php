<?php

namespace Database\Seeders;

use App\Domain\Rbac\Models\Permission;
use App\Domain\Rbac\Models\Role;
use App\Domain\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->truncate();
        Permission::query()->truncate();
        Role::query()->truncate();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(76)->create();

        Permission::factory(15)->create();

        Role::factory(10)->create();
    }
}
