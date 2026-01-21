<?php

namespace App\Domain\Call\Models;

use App\Domain\Call\Enums\CallType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    /** @use HasFactory<\Database\Factories\CallFactory> */
    use HasFactory;
    
    protected $fillable = [
        'ticket_id',
        'client_id',
        'user_id',
        'type',
        'lang',
        'extension',
        'unique_id',
        'started',
        'ended',
        'duration',
        'recording',
        'status',
        'redirected',
        'deleted',
    ];

    protected function casts(): array
    {
        return [
            'type' => CallType::class,
            'started' => 'datetime',
            'ended' => 'datetime',
            'redirected' => 'boolean',
            'deleted' => 'boolean',
        ];
    }
}
