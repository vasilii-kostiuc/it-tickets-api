<?php

namespace App\Domain\Ticket\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->ticket_pid ??= (string) Str::uuid();
        });
    }
}
