<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Broadcast;
use Illuminate\Support\Facades\Storage;

class AuthControllerApi extends Controller
{
    // Login
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $loginData['email'])->first();

        if (!$user || !Hash::check($loginData['password'], $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Ambil broadcast untuk user tersebut
        // $broadcasts = Broadcast::where(function ($query) use ($user) {
        //         $query->whereHas('recipients', function ($q) use ($user) {
        //             $q->where('user_id', $user->id);
        //         });
        //     })
        //     ->orWhere('send_to_all', true)
        //     ->with('sender')
        //     ->orderBy('created_at', 'desc')
        //     ->take(5)
        //     ->get();

        // foreach ($broadcasts as $broadcast) {
        //     $recipient = $broadcast->recipients()->where('user_id', $user->id)->first();
        //     $broadcast->is_read = $recipient && $recipient->pivot->read_at ? true : false;
        //     $broadcast->read_at = $recipient->pivot->read_at ?? null;
        //     $broadcast->file_url = $broadcast->file_path ? url(Storage::url($broadcast->file_path)) : null;
        // }

        return response([
            'user' => $user,
            'token' => $token,
            // 'broadcasts' => $broadcasts
        ], 200);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response(['message' => 'Logged out'], 200);
    }

    // Update image profile & face_embedding
    public function updateProfile(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'face_embedding' => 'required',
        ]);

        $user = $request->user();
        $image = $request->file('image');
        $face_embedding = $request->face_embedding;

        // // Simpan gambar (jika digunakan)
        $image->storeAs('public/images', $image->hashName());
        $user->image_url = $image->hashName();

        $user->face_embedding = $face_embedding;
        $user->save();

        return response([
            'message' => 'Profile updated',
            'user' => $user,
        ], 200);
    }

    // Update FCM token
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response([
            'message' => 'FCM token updated',
        ], 200);
    }


}
