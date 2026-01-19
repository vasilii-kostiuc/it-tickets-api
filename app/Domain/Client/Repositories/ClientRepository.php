<?php

namespace App\Domain\Client\Repositories;

use App\Domain\Client\Models\Client;

class ClientRepository implements ClientRepositoryInterface
{
    public function getClientByPhone(string $phone): Client|null
    {
        $client = Client::query()->where('phone', $phone)->first();

        return $client;
    }

    public function getClientByEmail(string $email): Client|null
    {
        $client = Client::query()->where('email', $email)->first();

        return $client;
    }

    public function existsByPhone(string $phone): bool
    {
        return Client::query()->where('phone', '=', $phone)->count() > 0;
    }

    public function existsByEmail(string $email): bool
    {
        return Client::query()->where('email', '=', $email)->count() > 0;
    }

    public function create(array $data): Client
    {
        $client = Client::query()->create($data);

        return $client;
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);

        return $client;
    }

}
