<?php

namespace App\Domain\Call\PhoneFormatters;

interface PhoneFormatterInterface
{
    public function format(string $phone): string;

    public function isValid(string $phone): bool;
}
