<?php

namespace App\Domain\Ticket\Models;

use App\Domain\Ticket\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\App\Domain\Ticket\Models\CategoryFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'is_public',
        'status',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'status' => CategoryStatus::class,
            'is_public' => 'boolean',
            'order' => 'integer',
        ];
    }
}
