<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

trait HandleAuth
{
    protected function getThrottleKey(Request $request)
    {
        return strtolower($request->input('email')).'|'.$request->ip();
    }

    /**
     * Login securely and issue Sanctum token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = $this->getThrottleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => ['Too many login attempts. Please try again later.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60); // lockout for 60 seconds
            $login = Auth::guard()->attempt($request->only('email', 'password')); // log the attemp
            if(!$login){
                throw ValidationException::withMessages([
                    'email' => ['These credentials do not match our records.'],
                ]);
            }
        }

        RateLimiter::clear($throttleKey);

        // Optional: revoke previous tokens
        $user->tokens()->delete();

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * Logout the user by revoking the current token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * Return authenticated user data
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
