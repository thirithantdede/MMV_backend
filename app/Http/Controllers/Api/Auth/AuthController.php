<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;
use DB;
use Illuminate\Http\JsonResponse;

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

    public function handleRegister(RegisterRequest $request): mixed
    {
        try {
            DB::beginTransaction();
            $response =  $this->authService->register($request,'web');
            $jsonResponse = json_decode($response->getContent(),true);
            $user = $jsonResponse['user'];
            $this->authService->newProjectSetup($user);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            return responseJson(['message' => $e->getMessage()], status: 401, isSuccess: false);
        }
    }
}
