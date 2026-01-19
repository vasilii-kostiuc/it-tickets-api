<?php

namespace App\Domain\Client\Repositories;

use App\Domain\Client\Models\Client;

interface ClientRepositoryInterface
{
    public function getClientByPhone(string $phone): Client|null;

    public function getClientByEmail(string $email): Client|null;

    public function existsByPhone(string $phone): bool;

    public function existsByEmail(string $email): bool;

    public function create(array $data): Client;

    public function update(Client $client, array $data): Client;
}
