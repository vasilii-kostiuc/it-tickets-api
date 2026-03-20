<?php

namespace App\Domain\Department\Models;

use App\Domain\Ticket\Models\Sla;
use App\Domain\Ticket\Models\Ticket;
use App\Domain\User\Models\User;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ext_mask',
        'queue1',
        'queue2',
        'manager_id',
        'sla_id',
    ];

    protected static function newFactory(): Factory
    {
        return DepartmentFactory::new();
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function sla(): BelongsTo
    {
        return $this->belongsTo(Sla::class);
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
