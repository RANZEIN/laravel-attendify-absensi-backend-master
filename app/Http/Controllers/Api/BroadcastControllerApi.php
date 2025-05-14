<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BroadcastControllerApi extends Controller
{
    public function getBroadcasts(Request $request)
    {
        $user = $request->user();
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // Get broadcasts for this user
        $broadcasts = Broadcast::where(function($query) use ($user) {
                $query->whereHas('recipients', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->orWhere('send_to_all', true)
            ->where('status', 'sent')
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Append read status and file_url
        $broadcasts->getCollection()->transform(function($broadcast) use ($user) {
            $recipient = $broadcast->recipients()->where('user_id', $user->id)->first();
            $broadcast->is_read = $recipient && $recipient->pivot->read_at ? true : false;
            $broadcast->read_at = $recipient && $recipient->pivot->read_at ? $recipient->pivot->read_at : null;

            // No need to add file_url as it's already in the appends array in the model

            return $broadcast;
        });

        return response()->json([
            'status' => 'Success',
            'message' => 'Broadcasts retrieved successfully',
            'data' => $broadcasts->items(),
            'pagination' => [
                'current_page' => $broadcasts->currentPage(),
                'last_page' => $broadcasts->lastPage(),
                'per_page' => $broadcasts->perPage(),
                'total' => $broadcasts->total()
            ]
        ]);
    }

    public function getBroadcastDetail(Request $request, $id)
    {
        $user = $request->user();
        $broadcast = Broadcast::with('sender')->find($id);

        if (!$broadcast) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Broadcast not found',
            ], 404);
        }

        $isRecipient = $broadcast->send_to_all ||
            $broadcast->recipients()->where('user_id', $user->id)->exists();

        if (!$isRecipient) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Unauthorized access to broadcast',
            ], 403);
        }

        $recipient = $broadcast->recipients()->where('user_id', $user->id)->first();

        if (!$recipient || !$recipient->pivot->read_at) {
            if (!$recipient) {
                $broadcast->recipients()->attach($user->id, ['read_at' => Carbon::now()]);
            } else {
                $broadcast->recipients()->updateExistingPivot($user->id, ['read_at' => Carbon::now()]);
            }
        }

        // No need to add file_url as it's already in the appends array in the model

        return response()->json([
            'status' => 'Success',
            'message' => 'Broadcast details retrieved successfully',
            'data' => $broadcast
        ]);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'broadcast_id' => 'required|exists:broadcasts,id',
        ]);

        $user = $request->user();
        $broadcast = Broadcast::find($request->broadcast_id);

        $isRecipient = $broadcast->recipients()->where('user_id', $user->id)->exists();

        if (!$isRecipient && !$broadcast->send_to_all) {
            return response()->json([
                'status' => 'Error',
                'message' => 'User is not a recipient of this broadcast',
            ], 403);
        }

        if (!$isRecipient) {
            $broadcast->recipients()->attach($user->id, ['read_at' => Carbon::now()]);
        } else {
            $broadcast->recipients()->updateExistingPivot($user->id, ['read_at' => Carbon::now()]);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Broadcast marked as read',
        ]);
    }

    public function registerDeviceToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'device_type' => 'required|in:android,ios',
        ]);

        $user = $request->user();

        UserDevice::updateOrCreate(
            ['user_id' => $user->id, 'device_token' => $request->device_token],
            ['device_type' => $request->device_type, 'last_active' => Carbon::now()]
        );

        return response()->json([
            'status' => 'Success',
            'message' => 'Device token registered successfully',
        ]);
    }

    public function getUnreadCount(Request $request)
    {
        $user = $request->user();

        $count = Broadcast::where(function($query) use ($user) {
                $query->whereHas('recipients', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->whereNull('broadcast_user.read_at');
                });
            })
            ->orWhere(function($query) use ($user) {
                $query->where('send_to_all', true)
                      ->whereDoesntHave('recipients', function($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->where('status', 'sent')
            ->count();

        return response()->json([
            'status' => 'Success',
            'message' => 'Unread count retrieved successfully',
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }

    public function downloadFile(Request $request, $id)
    {
        $user = $request->user();
        $broadcast = Broadcast::find($id);

        if (!$broadcast) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Broadcast not found',
            ], 404);
        }

        $isRecipient = $broadcast->send_to_all ||
            $broadcast->recipients()->where('user_id', $user->id)->exists();

        if (!$isRecipient) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Unauthorized access to broadcast file',
            ], 403);
        }

        if (!$broadcast->file_path) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No file attached to this broadcast',
            ], 404);
        }

        // Mark as read if not already
        $recipient = $broadcast->recipients()->where('user_id', $user->id)->first();
        if (!$recipient || !$recipient->pivot->read_at) {
            if (!$recipient) {
                $broadcast->recipients()->attach($user->id, ['read_at' => Carbon::now()]);
            } else {
                $broadcast->recipients()->updateExistingPivot($user->id, ['read_at' => Carbon::now()]);
            }
        }

        // Return file download URL with temporary signed URL if needed
        // For public storage, we can just return the URL
        return response()->json([
            'status' => 'Success',
            'message' => 'File URL retrieved successfully',
            'data' => [
                'file_url' => $broadcast->file_url,
                'file_name' => $broadcast->file_name,
                'file_type' => $broadcast->file_type
            ]
        ]);
    }
}
