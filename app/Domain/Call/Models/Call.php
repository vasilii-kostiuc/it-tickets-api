<?php

namespace App\Domain\Call\Models;

use App\Domain\Call\Enums\CallType;
use App\Domain\Client\Models\Client;
use App\Domain\Ticket\Models\Ticket;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // Relations

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
