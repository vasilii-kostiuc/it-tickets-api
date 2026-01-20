<?php

namespace App\Domain\Ticket\Models;

use App\Domain\Ticket\Enums\TicketSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{

    protected function casts(): array
    {
        return [
            'source' => TicketSource::class
        ];
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->ticket_pid ??= (string) Str::uuid();
        });
    }
}
