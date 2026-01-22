<?php

namespace App\Domain\Department\Models;

use App\Domain\Ticket\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    // Relations

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
