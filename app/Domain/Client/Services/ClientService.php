<?php

namespace App\Domain\Client\Services;

use App\Domain\Client\Models\Client;
use App\Domain\Client\Repositories\ClientRepository;
use App\Domain\Client\Repositories\ClientRepositoryInterface;

class ClientService
{
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getOrNewClientForPhone(string $phone, array $data = []): Client
    {
        if ($this->clientRepository->existsByPhone($phone)) {
            return $this->clientRepository->getClientByPhone($phone);
        }

        $client = $this->clientRepository->create(array_merge([
            'phone' => $phone,
        ], $data));

        return $client;
    }
    

    public function createNewClient(array $data): Client
    {
        $client = $this->clientRepository->create($data);

        return $client;
    }
}
