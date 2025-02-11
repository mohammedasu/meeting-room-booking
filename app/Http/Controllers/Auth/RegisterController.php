<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserSubscription;

class RegisterController extends Controller
{
    /**
     * Handle the user registration.
     */
    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $freePlan = Subscription::where('name', 'Free Plan')->first();
        UserSubscription::firstOrCreate(
            ['user_id' => $user->id],
            ['subscription_id' => $freePlan->id, 'status' => 'active']
        );

        // Return response
        return response()->json(['message' => 'User registered successfully.'], 201);
    }
}
