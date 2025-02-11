<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Handle an incoming login request.
     */
    public function login(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Check if too many login attempts have been made
        $key = 'login|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, config('auth.login_attempts'))) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json(['message' => 'Too many login attempts. Try again in ' . $seconds . ' seconds.'], 429);
        }
        
        if (Auth::attempt($request->only('email', 'password'))) {
            // Clear failed login attempts
            RateLimiter::clear($key);
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user]);
        }

        // Increment failed login attempts
        RateLimiter::hit($key, 60 * 24); // 24-hour lockout

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    /**
     * Handle the user logout.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
