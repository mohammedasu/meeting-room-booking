<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSubscription;

class CheckBookingLimit {
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();
        $subscription = UserSubscription::where('user_id', $user->id)->first();
        
        if ($subscription) {
            $limit = $subscription->subscription->booking_limit;
            if ($user->bookings()->whereDate('created_at', today())->count() >= $limit) {
                return response()->json(['message' => 'Booking limit reached.'], 403);
            }
        }
        
        return $next($request);
    }
}
