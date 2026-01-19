<?php

namespace App\Domain\Utils\Settings;

interface SettingsRepositoryInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function has(string $key): bool;

    public function all(): array;

}
