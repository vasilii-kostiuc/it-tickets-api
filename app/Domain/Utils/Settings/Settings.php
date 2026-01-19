<?php

namespace App\Domain\Utils\Settings;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingsRepositoryInterface::class;
    }
}
