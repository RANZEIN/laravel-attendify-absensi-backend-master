<?php

namespace App\Http\Controllers;

use App\Models\Broadcast;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BroadcastController extends Controller
{
    protected $pushNotificationService;

    // public function __construct(PushNotificationService $pushNotificationService)
    // {
    //     $this->pushNotificationService = $pushNotificationService;
    // }

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
            $this->pushNotificationService->sendBroadcastNotification($broadcast);
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

    public function publicBroadcasts()
    {
        $broadcasts = Broadcast::with('sender')
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $broadcasts
        ]);
    }

    // public function userController()
    // {
    //     $user = Auth::user();
    //     $broadcasts = $user->receivedBroadcasts()
    //         ->where('status', 'sent')
    //         ->orderBy('sent_at', 'desc')
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $broadcasts
    //     ]);
    // }


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
            $this->pushNotificationService->sendBroadcastNotification($broadcast);
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
        $this->pushNotificationService->sendBroadcastNotification($broadcast);

        return redirect()->route('broadcasts.index')
            ->with('success', 'Broadcast sent successfully');
    }
}
