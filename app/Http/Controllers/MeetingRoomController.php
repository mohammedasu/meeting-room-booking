<?php
namespace App\Http\Controllers;

use App\Models\MeetingRoom;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MeetingRoomController extends Controller
{
    public function availableRooms(Request $request)
    {
        $request->validate([
            'meeting_datetime' => 'required|date|after:now',
            'duration' => 'required|integer|in:30,60,90',
            'members' => 'required|integer|min:1',
        ]);

        $meetingStart = Carbon::parse($request->meeting_datetime);
        $meetingEnd = $meetingStart->copy()->addMinutes((int) $request->duration);

        // Get rooms that match capacity requirement
        $rooms = MeetingRoom::where('capacity', '>=', $request->members)->get();

        // Filter out booked rooms
        $availableRooms = $rooms->reject(function ($room) use ($meetingStart, $meetingEnd) {
            return Booking::where('meeting_room_id', $room->id)
                ->where(function ($query) use ($meetingStart, $meetingEnd) {
                    $query->whereBetween('meeting_datetime', [$meetingStart, $meetingEnd])
                          ->orWhereRaw('? BETWEEN meeting_datetime AND DATE_ADD(meeting_datetime, INTERVAL duration MINUTE)', [$meetingStart]);
                })
                ->exists();
        });

        return response()->json($availableRooms);
    }
}

// 61,637
