<?php

namespace App\Domain\User\Models;

use App\Domain\Call\Models\Call;
use App\Domain\Ticket\Models\Ticket;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'extension',
        'mobile',
        'lang',
        'timezone',
        'locale',
        'is_active',
        'is_admin',
        'is_visible',
        'on_vocation',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
            'is_visible' => 'boolean',
            'on_vocation' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

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
