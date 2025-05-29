<?php

namespace App\Traits;

use App\Models\GuestUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

trait HandleAuth
{
    /**
     * Generate a unique throttle key for rate limiting
     */
    protected function getThrottleKey(Request $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Register a new user with enhanced validation
     */
    public function register(Request $request, string $guard)
    {
        $table = $guard === 'web' ? 'users' : 'guest_users';
        $model = $guard === 'web' ? User::class : GuestUser::class;

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[\p{L}\s-]+$/u',
            'email' => 'required|email|max:255|unique:' . $table . ',email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|same:password',
        ]);

        try {
            $user = $model::create([
                'name' => trim($request->name),
                'email' => Str::lower($request->email),
                'password' => Hash::make($request->password),
            ]);

            // Log registration event
            Log::info('New user registered', [
                'email' => $user->email,
                'guard' => $guard,
                'ip' => $request->ip(),
            ]);

            // Auto-login after registration
            Auth::guard($guard)->login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Login securely with enhanced rate limiting and token management
     */
    public function login(Request $request, string $guard)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
            'remember' => 'boolean|nullable',
        ]);

        $throttleKey = $this->getThrottleKey($request);
        $maxAttempts = 5;
        $lockoutTime = 300; // 5 minutes

        // Check rate limiting
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
            ]);
        }

        try {
            $model = $guard === 'web' ? User::class : GuestUser::class;
            $user = $model::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                RateLimiter::hit($throttleKey, $lockoutTime);
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials provided.'],
                ]);
            }

            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);

            // Attempt authentication
            if (!Auth::guard($guard)->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ], $request->boolean('remember'))) {
                throw ValidationException::withMessages([
                    'email' => ['Authentication failed.'],
                ]);
            }

            // Revoke previous tokens
            $user->tokens()->delete();

            // Log successful login
            Log::info('User logged in', [
                'email' => $user->email,
                'guard' => $guard,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'type' => $guard === 'web'? 'web' : 'guest',
                'user' => $user,
                'token' => $user->createToken('auth_token', ['*'], $request->boolean('remember') ? now()->addWeeks(2) : null)->plainTextToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Login failed. Please try again.',
            ], 401);
        }
    }

    /**
     * Logout the user and clear session
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            Auth::guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('User logged out', [
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Return authenticated user data with additional details
     */
    public function me(Request $request)
    {
        try {
            $user = $request->user()->load('roles', 'permissions');
            
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'last_login' => $user->last_login_at ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch user data', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user data.',
            ], 500);
        }
    }
}