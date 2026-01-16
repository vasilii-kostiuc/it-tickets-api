<?php

namespace App\Domain\Call\Models;

use App\Domain\Call\Enums\CallType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    /** @use HasFactory<\Database\Factories\CallFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type'=> CallType::class
        ];
    }
}
