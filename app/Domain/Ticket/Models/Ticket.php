<?php

namespace App\Domain\Ticket\Models;

use App\Domain\Ticket\Enums\TicketSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_pid',
        'ticket_number',
        'user_id',
        'client_id',
        'ticket_status_id',
        'department_id',
        'sla_id',
        'category_id',
        'source',
        'is_overdue',
        'is_answered',
        'due_date',
        'est_due_date',
        'reopened_at',
        'closed_at',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'source' => TicketSource::class,
            'is_overdue' => 'boolean',
            'is_answered' => 'boolean',
            'due_date' => 'datetime',
            'est_due_date' => 'datetime',
            'reopened_at' => 'datetime',
            'closed_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->ticket_pid ??= (string) Str::uuid();
        });
    }
}
