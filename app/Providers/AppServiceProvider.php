<?php

namespace App\Providers;

use App\Domain\Call\PhoneFormatters\PhoneFormatterInterface;
use App\Domain\Call\PhoneFormatters\RawPhoneFormatter;
use App\Domain\Client\Repositories\ClientRepository;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Ticket\Services\SlaCalculators\DefaultSlaCalculator;
use App\Domain\Ticket\Services\SlaCalculators\SlaCalculatorInterface;
use App\Domain\Ticket\Services\TicketNumberGenerators\SequentialNumberGenerator;
use App\Domain\Ticket\Services\TicketNumberGenerators\TicketNumberGeneratorInterface;
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

        $this->app->bind(SlaCalculatorInterface::class, DefaultSlaCalculator::class);
        $this->app->bind(TicketNumberGeneratorInterface::class, SequentialNumberGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

