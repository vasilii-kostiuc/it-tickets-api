<?php

namespace App\Providers;

use App\Domain\Call\PhoneFormatters\PhoneFormatterInterface;
use App\Domain\Call\PhoneFormatters\RawPhoneFormatter;
use App\Domain\Client\Repositories\ClientRepository;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Utils\Settings\InMemorySettingsRepository;
use App\Domain\Utils\Settings\SettingsRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PhoneFormatterInterface::class, RawPhoneFormatter::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class, InmemorySettingsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
