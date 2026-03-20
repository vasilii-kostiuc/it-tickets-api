<?php

namespace App\Http\Controllers\Api\V1\Department;

use App\Domain\Department\Models\Department;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Department\DepartmentUserRequest;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Department\DepartmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $departments = QueryBuilder::for(Department::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('manager_id'),
                AllowedFilter::exact('sla_id'),
            ])
            ->allowedSorts(['name', 'created_at'])
            ->defaultSort('name')
            ->paginate($request->input('per_page', 10));

        $departments->getCollection()->transform(fn($dept) => new DepartmentResource($dept));

        return ApiResponseResource::paginated($departments);
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = Department::create($request->validated());

        return ApiResponseResource::success(new DepartmentResource($department), null, 201);
    }

    public function show(Department $department): JsonResponse
    {
        return ApiResponseResource::success(new DepartmentResource($department));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        $department->update($request->validated());

        return ApiResponseResource::success(new DepartmentResource($department));
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return ApiResponseResource::success();
    }

    public function addUser(DepartmentUserRequest $request, Department $department): JsonResponse
    {
        $department->users()->syncWithoutDetaching([$request->validated('user_id')]);

        return ApiResponseResource::success();
    }

    public function removeUser(Department $department, User $user): JsonResponse
    {
        $department->users()->detach($user->id);

        return ApiResponseResource::success();
    }
}
