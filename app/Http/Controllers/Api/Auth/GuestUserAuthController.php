<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class GuestUserAuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}
    

    public function handleLogin(LoginRequest $request){
        try {
            return $this->authService->login($request,'guest_user');
        } catch (\Exception $e) {
            return responseJson(['message' => $e->getMessage()], status: 401, isSuccess: false);
        }
    }

    public function handleRegister(Request $request){
        try {
            return $this->authService->register($request,'guest_user');
        } catch (\Exception $e) {
            return responseJson(['message' => $e->getMessage()], status: 401, isSuccess: false);
        }
    }
    
}
