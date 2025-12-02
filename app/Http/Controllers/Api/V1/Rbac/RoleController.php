<?php

namespace App\Http\Controllers\Api\V1\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rbac\StoreRoleRequest;
use App\Http\Requests\Rbac\UpdateRolePermissionsRequest;
use App\Http\Requests\Rbac\UpdateRoleRequest;
use App\Domain\Rbac\Services\RoleService;
use App\Domain\Rbac\Models\Role;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Rbac\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Guard;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request): JsonResponse
    {
        $models = QueryBuilder::for(Role::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('description'),
                AllowedFilter::exact('id'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'LIKE', "%{$value}%")
                            ->orWhere('description', 'LIKE', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([
                'name',
                'description',
                'created_at',
            ])
            ->defaultSort('name')
            ->paginate($request->input('per_page', 10));

        $models->getCollection()->transform(fn($model) => new RoleResource($model));

        return ApiResponseResource::paginated($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $attributes = $request->safe()->except('permissions');
        $permissions = ($request->safe(['permissions'])['permissions']) ?? [];

        $role = $this->roleService->create($attributes, $permissions);

        return ApiResponseResource::success(new RoleResource($role));
    }

    public function show(Role $role): JsonResponse
    {
        return ApiResponseResource::success(new RoleResource($role));
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $this->roleService->update($role, $request->validated());
        $this->roleService->updateRolePermissions($role, $request->validated('permissions') ?? []);

        return ApiResponseResource::success(new RoleResource($role));
    }


    public function destroy(Role $role): JsonResponse
    {
        //  dd( $role->attributes['guard_name'] ?? config('auth.defaults.guard'));
        //$model= getModelForGuard($role->attributes['guard_name'] ?? config('auth.defaults.guard'));
        //dd(config("auth.guards.sanctum.provider"));

        $this->roleService->remove($role);

        return ApiResponseResource::success(null);
    }

    public function updatePermissions(UpdateRolePermissionsRequest $request, Role $role): JsonResponse
    {
        $permissions = [];
        $permissionsRequest = $request->validated('permissions') ?? [];
        foreach ($permissionsRequest as $permission) {
            $permissions[] = (int)$permission;
        }

        $this->roleService->updateRolePermissions($role, $permissions);

        return ApiResponseResource::success(new RoleResource($role));
    }

    public function batchDelete(Request $request){
        $validated = $request->validate([
            'ids' => 'required|array|min:1|max:100', // Ограничение
            'ids.*' => 'integer|exists:permissions,id'
        ]);

        $this->roleService->massRemove( $validated['ids']);

        return ApiResponseResource::success();
    }

}
