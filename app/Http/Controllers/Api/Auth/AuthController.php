<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function handleLogin(LoginRequest $request)
    {
        try {
            return $this->authService->login($request,'web');
        } catch (\Exception $e) {
            return responseJson(['message' => $e->getMessage()], status: 401, isSuccess: false);
        }
    }
}
