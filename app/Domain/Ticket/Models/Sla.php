<?php

namespace App\Domain\Ticket\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sla extends Model
{
    /** @use HasFactory<\Database\Factories\SlaFactory> */
    use HasFactory;

    // Relations

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
