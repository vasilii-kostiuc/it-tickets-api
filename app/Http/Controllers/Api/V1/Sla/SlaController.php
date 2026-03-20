<?php

namespace App\Http\Controllers\Api\V1\Sla;

use App\Domain\Ticket\Models\Sla;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sla\StoreSlaRequest;
use App\Http\Requests\Sla\UpdateSlaRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Sla\SlaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SlaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $slas = QueryBuilder::for(Sla::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('id'),
            ])
            ->allowedSorts(['name', 'duration', 'created_at'])
            ->defaultSort('name')
            ->paginate($request->input('per_page', 10));

        $slas->getCollection()->transform(fn($sla) => new SlaResource($sla));

        return ApiResponseResource::paginated($slas);
    }

    public function store(StoreSlaRequest $request): JsonResponse
    {
        $sla = Sla::create($request->validated());

        return ApiResponseResource::success(new SlaResource($sla), null, 201);
    }

    public function show(Sla $sla): JsonResponse
    {
        return ApiResponseResource::success(new SlaResource($sla));
    }

    public function update(UpdateSlaRequest $request, Sla $sla): JsonResponse
    {
        $sla->update($request->validated());

        return ApiResponseResource::success(new SlaResource($sla));
    }

    public function destroy(Sla $sla): JsonResponse
    {
        $sla->delete();

        return ApiResponseResource::success();
    }
}
