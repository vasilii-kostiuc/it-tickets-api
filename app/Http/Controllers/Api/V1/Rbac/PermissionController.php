<?php

namespace App\Http\Controllers\Api\V1\Rbac;

use App\Domain\Rbac\Models\Permission;
use App\Domain\Rbac\Services\PermissionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rbac\StorePermissionRequest;
use App\Http\Requests\Rbac\UpdatePermissionRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Rbac\PermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionController extends Controller
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $models = QueryBuilder::for(Permission::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('display_name'),
                AllowedFilter::exact('id'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'LIKE', "%{$value}%")
                            ->orWhere('display_name', 'LIKE', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([
                'name',
                'display_name',
                'created_at',
            ])
            ->defaultSort('name')
            ->paginate($request->input('per_page', 10));

        $models->getCollection()->transform(fn($model) => new PermissionResource($model));

        return ApiResponseResource::paginated($models, 'Permissions retrieved successfully');
    }

    public function store(StorePermissionRequest $request): JsonResponse
    {
//        if (!auth()?->user()?->can('permissions.create')) {
//            return response()->redirectToRoute('dashboard');
//        }

        $permission = $this->permissionService->create($request->validated());

        return ApiResponseResource::success(new PermissionResource($permission), 'Permission created successfully');
    }

    public function show(Permission $permission): JsonResponse
    {
        return ApiResponseResource::success(new PermissionResource($permission));
    }

    public function update( Permission $permission, UpdatePermissionRequest $request): JsonResponse
    {
//        if (!auth()?->user()?->can('permissions.update')) {
//            return response()->redirectToRoute('dashboard');
//        }

        if($this->permissionService->update($permission, $request->validated())){
            return ApiResponseResource::success(new PermissionResource($permission), 'Permission updated successfully');
        }else
        {
            return ApiResponseResource::error(null, 'Permission not updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): JsonResponse
    {
//        if (!auth()?->user()?->can('permissions.delete')) {
//            return response()->redirectToRoute('dashboard');
//        }

        $this->permissionService->remove($permission);

        return ApiResponseResource::success(null, 'Permission deleted successfully');
    }

    public function batchDelete(Request $request){
        $validated = $request->validate([
            'ids' => 'required|array|min:1|max:100', // Ограничение
            'ids.*' => 'integer|exists:permissions,id'
        ]);

        $this->permissionService->massRemove( $validated['ids']);

        return ApiResponseResource::success();
    }
}
