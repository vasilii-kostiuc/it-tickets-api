<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @route GET /api/v1/users
     */
    public function index(Request $request): JsonResponse
    {
        //sleep(1);
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
                AllowedFilter::exact('id'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'LIKE', "%{$value}%")
                            ->orWhere('email', 'LIKE', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([
                'name',
                'email',
                'created_at',
            ])
            ->defaultSort('id')
            ->paginate($request->input('per_page', 10));

        $users->getCollection()->transform(fn($user) => new UserResource($user));

        return ApiResponseResource::paginated($users, 'Users retrieved successfully');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return ApiResponseResource::success(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return ApiResponseResource::success();
    }

    public function batchDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
        ]);

        User::destroy($validated['ids']);

        return ApiResponseResource::success();
    }
}
