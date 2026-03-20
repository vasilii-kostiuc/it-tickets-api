<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Domain\Client\Models\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Client\ClientResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $clients = QueryBuilder::for(Client::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('phone'),
                AllowedFilter::exact('id'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'LIKE', "%{$value}%")
                            ->orWhere('email', 'LIKE', "%{$value}%")
                            ->orWhere('phone', 'LIKE', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['name', 'email', 'phone', 'created_at'])
            ->defaultSort('name')
            ->paginate($request->input('per_page', 10));

        $clients->getCollection()->transform(fn($client) => new ClientResource($client));

        return ApiResponseResource::paginated($clients);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        return ApiResponseResource::success(new ClientResource($client), null, 201);
    }

    public function show(Client $client): JsonResponse
    {
        return ApiResponseResource::success(new ClientResource($client));
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return ApiResponseResource::success(new ClientResource($client));
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return ApiResponseResource::success();
    }
}
