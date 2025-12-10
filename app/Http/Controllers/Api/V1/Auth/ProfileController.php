<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Auth\ProfileResource;
use App\Domain\User\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
//use OpenApi\Attributes as OA;

class ProfileController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }

    public function show(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        return ApiResponseResource::success(new ProfileResource($user));
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        info( json_encode($request->keys()));
        $user = $this->userService->updateProfile(Auth::user(), $request->safe()->all());

        return ApiResponseResource::success(new ProfileResource($user));
    }
}
