<?php

namespace App\Domain\Client\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone1',
        'phone2',
    ];
}
