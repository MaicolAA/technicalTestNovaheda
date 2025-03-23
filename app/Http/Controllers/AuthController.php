<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Exception;

use App\Http\Request\Auth\RequestLogin;
use App\Http\Request\Auth\RequestRegister;
use App\Services\AuthService;
use App\Services\UserService;
use App\DTO\UserDto;

class AuthController extends MainController
{
    /**
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @param \App\Services\AuthService $authService
     * @param \App\Services\UserService $userService
     */
    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * Registra un nuevo usuario
     * @param \App\Http\Request\Auth\RequestRegister $request
     * @return JsonResponse
     */
    public function register(RequestRegister $request): JsonResponse
    {
        try {
            $request->validate($request->rules());

            $userDTO =  UserDto::fromRequest($request);
            $token = $this->authService->register($userDTO);

            return $this->created(
                ['token' => $token],
                __('messages.user_register_ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Inicia sesión
     * @param \App\Http\Request\Auth\RequestLogin $request
     * @return JsonResponse
     */
    public function login(RequestLogin $request): JsonResponse
    {
        try {
            $request->validate($request->rules());

            $userDTO =  UserDto::fromRequest($request);
            $token = $this->authService->login($userDTO);

            return $this->ok(
                ['token' => $token],
                __('messages.user_login_ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Cierra la sesión
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();
            return $this->message(__('messages.user_logout_ok'));
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Obtiene el usuario autenticado
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->getAuthenticatedUser();
            return $this->ok(
                $user->toArray(),
                __('messages.ok')
            );
        
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
