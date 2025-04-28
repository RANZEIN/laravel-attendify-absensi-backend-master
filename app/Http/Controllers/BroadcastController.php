<?php

namespace App\Http\Controllers;

use App\Models\Broadcast;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class BroadcastController extends Controller
{

    public function index()
    {
        $broadcasts = Broadcast::with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.broadcasts.index', compact('broadcasts'));
    }

    public function create()
    {
        $users = User::all();
        return view('pages.broadcasts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'file' => 'nullable|file|max:10240', // 10MB max
            'recipients' => 'required_without:send_to_all',
            'recipients.*' => 'exists:users,id',
            'send_to_all' => 'nullable|boolean',
            'send_now' => 'nullable|boolean'
        ]);

        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $filePath = $file->store('broadcasts', 'public');
        }

        $status = $request->send_now ? 'sent' : 'draft';
        $sentAt = $request->send_now ? Carbon::now() : null;

        $broadcast = Broadcast::create([
            'title' => $request->title,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'sender_id' => Auth::id(),
            'status' => $status,
            'sent_at' => $sentAt,
            'send_to_all' => $request->has('send_to_all') && $request->send_to_all ? true : false,
        ]);

        // If send to all is selected, attach all users
        if ($request->has('send_to_all') && $request->send_to_all) {
            $userIds = User::pluck('id')->toArray();
            $broadcast->recipients()->attach($userIds);
        } else {
            // Otherwise, attach only selected recipients
            $broadcast->recipients()->attach($request->recipients);
        }

        if ($request->send_now) {
            // Send push notifications to mobile app users
            $this->sendPushNotifications($broadcast);
        }

        return redirect()->route('broadcasts.index')
            ->with('success', $request->send_now ? 'Broadcast sent successfully' : 'Broadcast saved as draft');
    }

    public function show(Broadcast $broadcast)
    {
        $broadcast->load('sender', 'recipients');
        return view('pages.broadcasts.show', compact('broadcast'));
    }

    public function edit(Broadcast $broadcast)
    {
        if ($broadcast->status === 'sent') {
            return redirect()->route('broadcasts.index')
                ->with('error', 'Cannot edit a broadcast that has already been sent');
        }

        $users = User::all();
        $selectedRecipients = $broadcast->recipients->pluck('id')->toArray();

        return view('pages.broadcasts.edit', compact('broadcast', 'users', 'selectedRecipients'));
    }

    public function update(Request $request, Broadcast $broadcast)
    {
        if ($broadcast->status === 'sent') {
            return redirect()->route('broadcasts.index')
                ->with('error', 'Cannot update a broadcast that has already been sent');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'file' => 'nullable|file|max:10240', // 10MB max
            'recipients' => 'required_without:send_to_all',
            'recipients.*' => 'exists:users,id',
            'send_to_all' => 'nullable|boolean',
            'send_now' => 'nullable|boolean'
        ]);

        $filePath = $broadcast->file_path;
        $fileName = $broadcast->file_name;
        $fileType = $broadcast->file_type;

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($broadcast->file_path) {
                Storage::disk('public')->delete($broadcast->file_path);
            }

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $filePath = $file->store('broadcasts', 'public');
        } elseif ($request->has('remove_file') && $request->remove_file) {
            // Remove existing file
            if ($broadcast->file_path) {
                Storage::disk('public')->delete($broadcast->file_path);
            }
            $filePath = null;
            $fileName = null;
            $fileType = null;
        }

        $status = $request->send_now ? 'sent' : 'draft';
        $sentAt = $request->send_now ? Carbon::now() : null;

        $broadcast->update([
            'title' => $request->title,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'status' => $status,
            'sent_at' => $sentAt,
            'send_to_all' => $request->has('send_to_all') && $request->send_to_all ? true : false,
        ]);

        // Sync recipients
        if ($request->has('send_to_all') && $request->send_to_all) {
            // If send to all is selected, sync all users
            $userIds = User::pluck('id')->toArray();
            $broadcast->recipients()->sync($userIds);
        } else {
            // Otherwise, sync only selected recipients
            $broadcast->recipients()->sync($request->recipients);
        }

        if ($request->send_now) {
            // Send push notifications to mobile app users
            $this->sendPushNotifications($broadcast);
        }

        return redirect()->route('broadcasts.index')
            ->with('success', $request->send_now ? 'Broadcast sent successfully' : 'Broadcast updated');
    }

    public function destroy(Broadcast $broadcast)
    {
        // Delete file if exists
        if ($broadcast->file_path) {
            Storage::disk('public')->delete($broadcast->file_path);
        }

        $broadcast->delete();

        return redirect()->route('broadcasts.index')
            ->with('success', 'Broadcast deleted successfully');
    }

    public function send(Broadcast $broadcast)
    {
        if ($broadcast->status === 'sent') {
            return redirect()->route('broadcasts.index')
                ->with('error', 'Broadcast has already been sent');
        }

        // If broadcast is set to send to all, update recipients to include all users
        if ($broadcast->send_to_all) {
            $userIds = User::pluck('id')->toArray();
            $broadcast->recipients()->sync($userIds);
        }

        $broadcast->update([
            'status' => 'sent',
            'sent_at' => Carbon::now(),
        ]);

        // Send push notifications to mobile app users
        $this->sendPushNotifications($broadcast);

        return redirect()->route('broadcasts.index')
            ->with('success', 'Broadcast sent successfully');
    }

    private function sendPushNotifications(Broadcast $broadcast)
    {
        // This is a placeholder for your actual push notification implementation
        // You would typically use a service like Firebase Cloud Messaging (FCM)

        // Example implementation with FCM would be:
        // 1. Get all recipient tokens
        // 2. Send batch notification

        // For now, we'll just log that we would send notifications
        Log::info('Push notifications would be sent for broadcast: ' . $broadcast->id);

        // In a real implementation, you might use something like:
        /*
        $recipients = $broadcast->recipients;
        $tokens = [];

        foreach ($recipients as $recipient) {
            // Assuming you store FCM tokens in a user_devices table
            $devices = $recipient->devices;
            foreach ($devices as $device) {
                $tokens[] = $device->fcm_token;
            }
        }

        if (count($tokens) > 0) {
            $fcmData = [
                'registration_ids' => $tokens,
                'notification' => [
                    'title' => $broadcast->title,
                    'body' => $broadcast->message,
                ],
                'data' => [
                    'broadcast_id' => $broadcast->id,
                    'type' => 'broadcast'
                ]
            ];

            $headers = [
                'Authorization: key=' . config('services.fcm.key'),
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmData));
            $result = curl_exec($ch);
            curl_close($ch);

            \Log::info('FCM Response: ' . $result);
        }
        */
    }
}
