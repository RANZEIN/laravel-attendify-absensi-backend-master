<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BroadcastController extends Controller
{
    public function getBroadcasts(Request $request)
    {
        $userId = $request->user_id;
        $user = User::find($userId);

        if (!$user) {
            return response([
                'status' => 'Error',
                'message' => 'User not found',
            ], 404);
        }

        // Get broadcasts for this user with read status
        // Include broadcasts sent to all users and broadcasts specifically sent to this user
        $broadcasts = Broadcast::where(function($query) use ($userId) {
                // Broadcasts specifically sent to this user
                $query->whereHas('recipients', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })
            ->orWhere('send_to_all', true) // Or broadcasts sent to all users
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // For each broadcast, check if the user has read it
        foreach ($broadcasts as $broadcast) {
            $readStatus = $broadcast->recipients()
                ->where('user_id', $userId)
                ->first();

            $broadcast->is_read = $readStatus && $readStatus->pivot->read_at ? true : false;
            $broadcast->read_at = $readStatus && $readStatus->pivot->read_at ? $readStatus->pivot->read_at : null;
        }

        return response([
            'status' => 'Success',
            'message' => 'Broadcasts retrieved successfully',
            'data' => $broadcasts
        ], 200);
    }

    public function getBroadcastDetail(Request $request, $id)
    {
        $userId = $request->user_id;
        $user = User::find($userId);

        if (!$user) {
            return response([
                'status' => 'Error',
                'message' => 'User not found',
            ], 404);
        }

        $broadcast = Broadcast::with('sender')->find($id);

        if (!$broadcast) {
            return response([
                'status' => 'Error',
                'message' => 'Broadcast not found',
            ], 404);
        }

        // Check if user is a recipient of this broadcast or if it's sent to all users
        $isRecipient = $broadcast->recipients()->where('user_id', $userId)->exists() || $broadcast->send_to_all;

        if (!$isRecipient) {
            return response([
                'status' => 'Error',
                'message' => 'Unauthorized access to broadcast',
            ], 403);
        }

        // Mark as read if not already
        $recipientRecord = $broadcast->recipients()->where('user_id', $userId)->first();

        if (!$recipientRecord || !$recipientRecord->pivot->read_at) {
            // If the user doesn't have a record in the pivot table (for broadcasts sent to all)
            // or if they haven't read it yet, mark it as read
            if (!$recipientRecord) {
                // Add the user to recipients if they're not already there (for broadcasts sent to all)
                $broadcast->recipients()->attach($userId, ['read_at' => Carbon::now()]);
            } else {
                // Update existing record
                $broadcast->recipients()->updateExistingPivot($userId, ['read_at' => Carbon::now()]);
            }
        }

        // Get file URL if exists
        if ($broadcast->file_path) {
            $broadcast->file_url = url(Storage::url($broadcast->file_path));
        }

        return response([
            'status' => 'Success',
            'message' => 'Broadcast details retrieved successfully',
            'data' => $broadcast
        ], 200);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'broadcast_id' => 'required|exists:broadcasts,id',
        ]);

        $userId = $request->user_id;
        $broadcastId = $request->broadcast_id;

        $broadcast = Broadcast::find($broadcastId);

        // Check if user is a recipient of this broadcast
        $isRecipient = $broadcast->recipients()->where('user_id', $userId)->exists();

        if (!$isRecipient) {
            return response([
                'status' => 'Error',
                'message' => 'User is not a recipient of this broadcast',
            ], 403);
        }

        // Mark as read
        $broadcast->recipients()->updateExistingPivot($userId, ['read_at' => Carbon::now()]);

        return response([
            'status' => 'Success',
            'message' => 'Broadcast marked as read',
        ], 200);
    }

    public function registerDeviceToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_token' => 'required|string',
            'device_type' => 'required|in:android,ios',
        ]);

        $userId = $request->user_id;
        $deviceToken = $request->device_token;
        $deviceType = $request->device_type;

        // Store or update device token
        // This assumes you have a user_devices table
        // You may need to create this table and model

        // Example implementation:
        /*
        $device = UserDevice::updateOrCreate(
            ['user_id' => $userId, 'device_token' => $deviceToken],
            ['device_type' => $deviceType, 'last_active' => Carbon::now()]
        );
        */

        return response([
            'status' => 'Success',
            'message' => 'Device token registered successfully',
        ], 200);
    }
}
