<?php

namespace App\Domain\Settings;

use App\Domain\Settings\SettingsRepositoryInterface;

class InMemorySettingsRepository implements SettingsRepositoryInterface
{
    private array $settings = [
        'default_sla' => 1,
        'maintenance_mode' => false,
        'max_upload_size' => 2048,
    ];


    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->settings[$key]);
    }

    public function all(): array
    {
        return $this->settings;
    }
}
