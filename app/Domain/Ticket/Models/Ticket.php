<?php

namespace App\Domain\Ticket\Models;

use App\Domain\Client\Models\Client;
use App\Domain\Department\Models\Department;
use App\Domain\Ticket\Enums\TicketSource;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    // Relations
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sla(): BelongsTo
    {
        return $this->belongsTo(Sla::class);
    }
}
