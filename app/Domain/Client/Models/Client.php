<?php

namespace App\Domain\Client\Models;

use App\Domain\Call\Models\Call;
use App\Domain\Ticket\Models\Ticket;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return ClientFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone1',
        'phone2',
    ];

    // Relations

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }
}
