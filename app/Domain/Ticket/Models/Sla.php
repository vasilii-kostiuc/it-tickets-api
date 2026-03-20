<?php

namespace App\Domain\Ticket\Models;

use App\Domain\Department\Models\Department;
use Database\Factories\SlaFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sla extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'schedule_id',
        'grace_duration',
        'description',
    ];

    protected static function newFactory(): Factory
    {
        return SlaFactory::new();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
