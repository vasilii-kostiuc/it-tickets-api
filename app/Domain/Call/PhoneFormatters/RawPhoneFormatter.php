<?php

namespace App\Domain\Call\PhoneFormatters;

use App\Domain\Call\PhoneFormatters\PhoneFormatterInterface;

class RawPhoneFormatter implements PhoneFormatterInterface
{
    public function format(string $phone): string
    {
        return $phone;
    }

    public function isValid(string $phone): bool
    {
        return true;
    }
}
