<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserControllerApi extends Controller
{
    // Get list user (with optional search and pagination)
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'status' => 'Success',
            'message' => 'Users fetched',
            'data' => $users,
        ], 200);
    }

    // Get single user by ID
    public function getUserId($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'User found',
            'data' => $user,
        ], 200);
    }

    // Store (create) user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
            'phone'     => 'nullable',
            'role'      => 'nullable',
            'position'  => 'nullable',
            'department'=> 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
            'role'      => $request->role,
            'position'  => $request->position,
            'department'=> $request->department,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'User not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $id,
            'phone'     => 'nullable',
            'role'      => 'nullable',
            'position'  => 'nullable',
            'department'=> 'nullable',
            'password'  => 'nullable|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'role'      => $request->role,
            'position'  => $request->position,
            'department'=> $request->department,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'User deleted successfully',
        ], 200);
    }


    // Update profile with image upload (optional)
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'id'        => 'required|exists:users,id',
                'name'      => 'required',
                'email'     => 'required|email',
                'phone'     => 'required',
                'image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $filePath = $image->storeAs('images/users', $image_name, 'public');
                $user->image_url = $filePath;
            }

            $user->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'User profile updated',
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'Error',
                'message' => $th->getMessage(),
            ], 500);
        }


    }

}
