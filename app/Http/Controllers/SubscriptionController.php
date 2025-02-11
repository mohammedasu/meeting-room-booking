<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller {

    public function index() {
        return response()->json(Subscription::all());
    }

    public function subscribe(Request $request) {
        $request->validate(['subscription_id' => 'required|exists:subscriptions,id', 'payment_reference' => 'nullable|string']);
        
        $user = Auth::user();
        UserSubscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'subscription_id' => $request->subscription_id,
                'status' => 'pending',
                'payment_reference' => $request->payment_reference
            ]
        );
        
        return response()->json(['message' => 'Subscription updated successfully, awaiting confirmation.']);
    }

    public function getUserSubscription() {
        $user = Auth::user();
        $subscription = UserSubscription::where('user_id', $user->id)->with('subscription')->first();
        
        return response()->json($subscription);
    }
}
