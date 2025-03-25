<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Dto\UserDto;


class UserService
{
    public function createUser(UserDto $userDTO)
    {
        return User::create([
            'name' => $userDTO->getName(),
            'email' => $userDTO->getEmail(),
            'password' => Hash::make($userDTO->getPassword()),
        ]);
    }

    public function getAuthenticatedUser()
    {
        return JWTAuth::user();
    }
}
