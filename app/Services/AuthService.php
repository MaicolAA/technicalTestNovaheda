<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;


use App\Services\UserService;
use App\DTO\UserDto;

class AuthService
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(UserDto $userDTO)
    {
        $user = $this->userService->createUser($userDTO);
        return JWTAuth::fromUser($user);
    }

    public function login(UserDto $userDto)
    {
        if (!$token = JWTAuth::attempt([
            'email' => $userDto->getEmail(),
            'password' => $userDto->getPassword(),
        ])) {
            throw ValidationException::withMessages(['email' => __('messages.error_email_password')]);
        }

        return $token;
    }


    public function getAuthenticatedUser(): UserDto
    {

        $user = Auth::guard('api')->user(); 
        if (!$user) {
            throw new Exception(__('messages.error_token'));
        }
        return UserDto::fromModel($user);
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

}
