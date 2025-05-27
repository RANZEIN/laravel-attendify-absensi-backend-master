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
        $priority = $request->input('priority'); // Filter berdasarkan priority
        $status = $request->input('status'); // Filter berdasarkan status (read/unread)
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Get broadcasts for this user
        $query = Broadcast::where(function($query) use ($user) {
                $query->whereHas('recipients', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->orWhere('send_to_all', true)
            ->where('status', 'sent')
            ->with('sender');

        // Filter berdasarkan priority jika ada
        if ($priority) {
            $query->where('priority', $priority);
        }

        // Filter berdasarkan status read/unread
        if ($status === 'read') {
            $query->whereHas('recipients', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereNotNull('broadcast_recipients.read_at');
            });
        } elseif ($status === 'unread') {
            $query->where(function($q) use ($user) {
                $q->whereHas('recipients', function($subq) use ($user) {
                    $subq->where('user_id', $user->id)
                         ->whereNull('broadcast_recipients.read_at');
                })->orWhere(function($subq) use ($user) {
                    $subq->where('send_to_all', true)
                         ->whereDoesntHave('recipients', function($subsubq) use ($user) {
                             $subsubq->where('user_id', $user->id);
                         });
                });
            });
        }

        // Sorting
        $query->orderBy($sortBy, $sortOrder);

        $broadcasts = $query->paginate($perPage, ['*'], 'page', $page);

        // Append read status and file_url
        $broadcasts->getCollection()->transform(function($broadcast) use ($user) {
            $recipient = $broadcast->recipients()->where('user_id', $user->id)->first();
            $broadcast->is_read = $recipient && $recipient->pivot->read_at ? true : false;
            $broadcast->read_at = $recipient && $recipient->pivot->read_at ? $recipient->pivot->read_at : null;

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

        // Set read status
        $broadcast->is_read = true;
        $broadcast->read_at = Carbon::now();

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

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        // Get all broadcasts for this user
        $broadcasts = Broadcast::where(function($query) use ($user) {
                $query->whereHas('recipients', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->whereNull('broadcast_recipients.read_at');
                });
            })
            ->orWhere(function($query) use ($user) {
                $query->where('send_to_all', true)
                      ->whereDoesntHave('recipients', function($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->where('status', 'sent')
            ->get();

        foreach ($broadcasts as $broadcast) {
            $isRecipient = $broadcast->recipients()->where('user_id', $user->id)->exists();

            if (!$isRecipient) {
                $broadcast->recipients()->attach($user->id, ['read_at' => Carbon::now()]);
            } else {
                $broadcast->recipients()->updateExistingPivot($user->id, ['read_at' => Carbon::now()]);
            }
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'All broadcasts marked as read',
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
                      ->whereNull('broadcast_recipients.read_at');
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

    public function getBroadcastsByPriority(Request $request, $priority)
    {
        // Validate priority
        if (!in_array($priority, ['low', 'medium', 'high'])) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid priority provided',
            ], 400);
        }

        $user = $request->user();
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // Get broadcasts for this user with specified priority
        $broadcasts = Broadcast::where(function($query) use ($user) {
                $query->whereHas('recipients', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->orWhere('send_to_all', true)
            ->where('status', 'sent')
            ->where('priority', $priority)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Append read status and file_url
        $broadcasts->getCollection()->transform(function($broadcast) use ($user) {
            $recipient = $broadcast->recipients()->where('user_id', $user->id)->first();
            $broadcast->is_read = $recipient && $recipient->pivot->read_at ? true : false;
            $broadcast->read_at = $recipient && $recipient->pivot->read_at ? $recipient->pivot->read_at : null;

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

    public function createBroadcast(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'priority' => 'required|in:low,medium,high',
        'send_to_all' => 'required|boolean',
        'recipients' => 'nullable|array',
        'recipients.*' => 'exists:users,id',
        'file' => 'nullable|file|max:20480', // max 20MB
    ]);

    $user = $request->user();

    // Simpan file jika ada
    $filePath = null;
    $fileName = null;
    $fileType = null;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('broadcasts/files');
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientMimeType();
    }

    // Simpan broadcast
    $broadcast = Broadcast::create([
        'title' => $request->title,
        'message' => $request->message,
        'priority' => $request->priority,
        'send_to_all' => $request->send_to_all,
        'status' => 'sent',
        'sender_id' => $user->id,
        'file_path' => $filePath,
        'file_name' => $fileName,
        'file_type' => $fileType,
    ]);

    // Jika tidak dikirim ke semua, maka simpan penerima
    if (!$request->send_to_all && $request->recipients) {
        $broadcast->recipients()->attach($request->recipients);
    }

    return response()->json([
        'status' => 'Success',
        'message' => 'Broadcast created successfully',
        'data' => $broadcast
    ]);
}
}
