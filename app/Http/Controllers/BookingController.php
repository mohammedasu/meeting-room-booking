<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        $currentSubscription = $user->currentSubscription();

        if ($currentSubscription) {
            $maxBookings = $currentSubscription->booking_limit;
        } else {
            $maxBookings = 0;
        }
        
        $todayBookings = Booking::where('user_id', $user->id)->count();
        if ($todayBookings != 0 && $todayBookings >= $maxBookings) {
            return response()->json(['error' => 'Booking limit reached'], 403);
        }

        $validator = Validator::make($request->all(), [
            'meeting_name' => 'required|string',
            'meeting_datetime' => 'required|date|after:now',
            'duration' => 'required|in:30,60,90',
            'members' => 'required|integer|min:1',
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = MeetingRoom::find($request->meeting_room_id);
        if ($request->members > $room->capacity) {
            return response()->json(['error' => 'Exceeds room capacity'], 400);
        }

        Booking::create([
            'user_id' => $user->id,
            'meeting_room_id' => $request->meeting_room_id,
            'meeting_name' => $request->meeting_name,
            'meeting_datetime' => $request->meeting_datetime,
            'duration' => $request->duration,
            'members' => $request->members,
        ]);

        return response()->json(['message' => 'Booking successful'], 201);
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->orderBy('date_time', 'desc')
            ->paginate(10);

        return response()->json($bookings);
    }

    public function getBookings(Request $request)
{
    $user = Auth::user();

    $filter = $request->query('filter', 'upcoming');

    $now = Carbon::now();

    // Filter the bookings
    if ($filter === 'upcoming') {
        $bookings = Booking::where('user_id', $user->id)
            ->where('meeting_datetime', '>=', $now)
            ->orderBy('meeting_datetime', 'asc')
            ->paginate(2);
    } else {
        $bookings = Booking::where('user_id', $user->id)
            ->where('meeting_datetime', '<', $now)
            ->orderBy('meeting_datetime', 'desc')
            ->paginate(2);
    }

    return response()->json($bookings);
}
}
