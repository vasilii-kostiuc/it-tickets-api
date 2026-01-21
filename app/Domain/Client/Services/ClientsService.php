<?php

namespace App\Domain\Client\Services;

use App\Domain\Client\Models\Client;
use App\Domain\Client\Repositories\ClientsRepository;
use App\Domain\Client\Repositories\ClientsRepositoryInterface;

class ClientsService
{
    private ClientsRepositoryInterface $clientsRepository;

    public function __construct(ClientsRepositoryInterface $clientsRepository)
    {
        $this->clientsRepository = $clientsRepository;
    }

    public function newClientForPhone(string $phone, array $data = []): Client
    {
        $client = $this->clientsRepository->create(array_merge([
            'phone' => $phone,
        ], $data));

        
        return $client;
    }

}
