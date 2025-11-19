<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Domain\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\Auth\RegisterResponseResource;

class RegisterController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(RegisterUserRequest $request)
    {
        sleep(2);
        $user = $this->userService->register($request->get('email'),$request->get('password'), $request->get('name'), );
        $device = $request->userAgent() ?? '';

        $accessToken = $user->createToken($device)->plainTextToken;

        $user->refresh();

        return new ApiResponseResource([
            'data' => new RegisterResponseResource((object)
            [
                'token' => $accessToken,
                'user' => $user,
            ])
        ]);
    }
}
