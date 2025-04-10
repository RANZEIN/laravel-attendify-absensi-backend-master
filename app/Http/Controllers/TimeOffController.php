<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeOff;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TimeOffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Handle search by name if provided
        $query = TimeOff::with('user');

        if ($request->has('name') && !empty($request->name)) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Get all time offs with pagination
        $allTimeOffs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get time offs by status for tabs
        $pendingTimeOffs = TimeOff::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedTimeOffs = TimeOff::with('user')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $rejectedTimeOffs = TimeOff::with('user')
            ->where('status', 'rejected')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.time_offs.index', compact(
            'allTimeOffs',
            'pendingTimeOffs',
            'approvedTimeOffs',
            'rejectedTimeOffs'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('pages.time_offs.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:annual_leave,sick_leave,unpaid_leave',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'document' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Calculate number of days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $endDate->diffInDays($startDate) + 1;

        // Handle document upload
        $documentUrl = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/time_off', $fileName, 'public');
            $documentUrl = $filePath;
        }

        // Create time off request
        $timeOff = new TimeOff();
        $timeOff->user_id = $request->user_id;
        $timeOff->type = $request->type;
        $timeOff->start_date = $request->start_date;
        $timeOff->end_date = $request->end_date;
        $timeOff->days = $days;
        $timeOff->reason = $request->reason;
        $timeOff->status = $request->status;
        $timeOff->document_url = $documentUrl;
        $timeOff->save();

        // If approved and annual leave, update user's leave balance
        if ($request->status === 'approved' && $request->type === 'annual_leave') {
            $user = User::find($request->user_id);
            if ($user && $user->leave_balance >= $days) {
                $user->leave_balance -= $days;
                $user->save();
            }
        }

        // For sick leave, create attendance records if approved
        if ($request->type === 'sick_leave' && $request->status === 'approved') {
            $this->createAttendanceForSickLeave(
                $request->user_id,
                $request->start_date,
                $request->end_date,
                $request->reason
            );
        }

        return redirect()->route('time_offs.index')
            ->with('success', 'Time off request created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $timeOff = TimeOff::with('user')->findOrFail($id);
        return view('pages.time_offs.show', compact('timeOff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timeOff = TimeOff::findOrFail($id);
        $users = User::all();
        return view('pages.time_offs.edit', compact('timeOff', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:annual_leave,sick_leave,unpaid_leave',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'document' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $timeOff = TimeOff::findOrFail($id);

        // Store old values for comparison
        $oldStatus = $timeOff->status;
        $oldType = $timeOff->type;
        $oldDays = $timeOff->days;
        $oldUserId = $timeOff->user_id;

        // Calculate number of days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $endDate->diffInDays($startDate) + 1;

        // Handle document upload
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($timeOff->document_url) {
                Storage::disk('public')->delete($timeOff->document_url);
            }

            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/time_off', $fileName, 'public');
            $timeOff->document_url = $filePath;
        }

        // Update time off request
        $timeOff->user_id = $request->user_id;
        $timeOff->type = $request->type;
        $timeOff->start_date = $request->start_date;
        $timeOff->end_date = $request->end_date;
        $timeOff->days = $days;
        $timeOff->reason = $request->reason;

        // Check if status changed
        $statusChanged = $oldStatus !== $request->status;
        $timeOff->status = $request->status;
        $timeOff->save();

        // Handle leave balance updates for annual leave
        if ($timeOff->type === 'annual_leave') {
            $user = User::find($timeOff->user_id);

            // If newly approved, deduct from balance
            if ($statusChanged && $request->status === 'approved' && $user) {
                if ($user->leave_balance >= $days) {
                    $user->leave_balance -= $days;
                    $user->save();
                }
            }

            // If was approved but now rejected/pending, restore balance
            if ($statusChanged && $oldStatus === 'approved' && $request->status !== 'approved' && $user) {
                $user->leave_balance += $oldDays;
                $user->save();
            }

            // If approved but days changed, adjust balance
            if (!$statusChanged && $request->status === 'approved' && $oldDays !== $days && $user) {
                $difference = $oldDays - $days;
                $user->leave_balance += $difference;
                $user->save();
            }
        }

        // For sick leave, create attendance records if newly approved
        if ($timeOff->type === 'sick_leave' && $statusChanged && $request->status === 'approved') {
            $this->createAttendanceForSickLeave(
                $timeOff->user_id,
                $timeOff->start_date,
                $timeOff->end_date,
                $timeOff->reason
            );
        }

        return redirect()->route('time_offs.index')
            ->with('success', 'Time off request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timeOff = TimeOff::findOrFail($id);

        // If approved annual leave, restore leave balance
        if ($timeOff->status === 'approved' && $timeOff->type === 'annual_leave') {
            $user = User::find($timeOff->user_id);
            if ($user) {
                $user->leave_balance += $timeOff->days;
                $user->save();
            }
        }

        // Delete document if exists
        if ($timeOff->document_url) {
            Storage::disk('public')->delete($timeOff->document_url);
        }

        $timeOff->delete();

        return redirect()->route('time_offs.index')
            ->with('success', 'Time off request deleted successfully.');
    }

    /**
     * Approve a time off request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $timeOff = TimeOff::findOrFail($id);

        // Only update if not already approved
        if ($timeOff->status !== 'approved') {
            $timeOff->status = 'approved';
            $timeOff->save();

            // If annual leave, update user's leave balance
            if ($timeOff->type === 'annual_leave') {
                $user = User::find($timeOff->user_id);
                if ($user && $user->leave_balance >= $timeOff->days) {
                    $user->leave_balance -= $timeOff->days;
                    $user->save();
                }
            }

            // For sick leave, create attendance records
            if ($timeOff->type === 'sick_leave') {
                $this->createAttendanceForSickLeave(
                    $timeOff->user_id,
                    $timeOff->start_date,
                    $timeOff->end_date,
                    $timeOff->reason
                );
            }
        }

        return redirect()->back()
            ->with('success', 'Time off request approved successfully.');
    }

    /**
     * Reject a time off request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $timeOff = TimeOff::findOrFail($id);

        // If it was approved and is annual leave, restore leave balance
        if ($timeOff->status === 'approved' && $timeOff->type === 'annual_leave') {
            $user = User::find($timeOff->user_id);
            if ($user) {
                $user->leave_balance += $timeOff->days;
                $user->save();
            }
        }

        $timeOff->status = 'rejected';
        $timeOff->save();

        return redirect()->back()
            ->with('success', 'Time off request rejected successfully.');
    }

    /**
     * Create attendance records for sick leave
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @param string $reason
     * @return void
     */
    private function createAttendanceForSickLeave($userId, $startDate, $endDate, $reason)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $end->diffInDays($start) + 1;

        $currentDate = $start->copy();

        for ($i = 0; $i < $days; $i++) {
            // Skip weekends if needed
            if ($currentDate->isWeekend()) {
                $currentDate->addDay();
                continue;
            }

            // Check if attendance record already exists for this date
            $existingAttendance = Attendance::where('user_id', $userId)
                ->where('date', $currentDate->format('Y-m-d'))
                ->first();

            if (!$existingAttendance) {
                // Create attendance record with sick leave status
                Attendance::create([
                    'user_id' => $userId,
                    'date' => $currentDate->format('Y-m-d'),
                    'time_in' => '09:00:00', // Default check-in time
                    'time_out' => '17:00:00', // Default check-out time
                    'latlon_in' => '0,0', // Default location
                    'latlon_out' => '0,0', // Default location
                    'status' => 'sick_leave', // Special status for sick leave
                    'reason' => $reason
                ]);
            }

            $currentDate->addDay();
        }
    }

    /**
     * Get leave statistics for dashboard
     *
     * @return array
     */
    public function getLeaveStatistics($userId = null)
    {
        // If no user ID provided, use authenticated user
        if (!$userId && Auth::check()) {
            $userId = Auth::id();
        }

        // Default values
        $stats = [
            'annualLeaveTotal' => 16, // Default annual leave allowance
            'annualLeaveUsed' => 0,
            'annualLeaveRemaining' => 16,
            'sickLeaveTotal' => 10, // Default sick leave allowance
            'sickLeaveUsed' => 0,
            'sickLeaveRemaining' => 10,
            'unpaidLeaveUsed' => 0
        ];

        if ($userId) {
            $user = User::find($userId);

            if ($user) {
                // Get current year
                $currentYear = Carbon::now()->year;

                // Annual leave used this year
                $annualLeaveUsed = TimeOff::where('user_id', $userId)
                    ->where('type', 'annual_leave')
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days');

                // Sick leave used this year
                $sickLeaveUsed = TimeOff::where('user_id', $userId)
                    ->where('type', 'sick_leave')
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days');

                // Unpaid leave used this year
                $unpaidLeaveUsed = TimeOff::where('user_id', $userId)
                    ->where('type', 'unpaid_leave')
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days');

                // Update stats
                $stats['annualLeaveRemaining'] = $user->leave_balance;
                $stats['annualLeaveUsed'] = $annualLeaveUsed;
                $stats['annualLeaveTotal'] = $stats['annualLeaveRemaining'] + $annualLeaveUsed;
                $stats['sickLeaveUsed'] = $sickLeaveUsed;
                $stats['sickLeaveRemaining'] = $stats['sickLeaveTotal'] - $sickLeaveUsed;
                $stats['unpaidLeaveUsed'] = $unpaidLeaveUsed;
            }
        }

        return $stats;
    }

    /**
     * Get recent time off requests for dashboard
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentTimeOffs($limit = 5)
    {
        return TimeOff::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * API Methods for Mobile App
     */

    /**
     * API: Get all time off requests for the authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apiGetTimeOffs(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $timeOffs = TimeOff::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Time off requests retrieved successfully',
            'data' => $timeOffs
        ]);
    }

    /**
     * API: Create a new time off request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apiCreateTimeOff(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $validator = validator($request->all(), [
            'type' => 'required|in:annual_leave,sick_leave,unpaid_leave',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Calculate number of days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $endDate->diffInDays($startDate) + 1;

        // Create time off request
        $timeOff = new TimeOff();
        $timeOff->user_id = $user->id;
        $timeOff->type = $request->type;
        $timeOff->start_date = $request->start_date;
        $timeOff->end_date = $request->end_date;
        $timeOff->days = $days;
        $timeOff->reason = $request->reason;
        $timeOff->status = 'pending'; // Always pending for new requests from mobile
        $timeOff->document_url = $request->attachment_url;
        $timeOff->save();

        return response()->json([
            'message' => 'Time off request created successfully',
            'data' => $timeOff
        ], 201);
    }

    /**
     * API: Get a specific time off request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function apiGetTimeOff($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $timeOff = TimeOff::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$timeOff) {
            return response()->json([
                'message' => 'Time off request not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Time off request retrieved successfully',
            'data' => $timeOff
        ]);
    }

    /**
     * API: Update a time off request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function apiUpdateTimeOff(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $timeOff = TimeOff::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$timeOff) {
            return response()->json([
                'message' => 'Time off request not found',
            ], 404);
        }

        // Only allow updates if the request is still pending
        if ($timeOff->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot update time off request that has been ' . $timeOff->status,
            ], 422);
        }

        $validator = validator($request->all(), [
            'type' => 'required|in:annual_leave,sick_leave,unpaid_leave',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Calculate number of days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $endDate->diffInDays($startDate) + 1;

        // Update time off request
        $timeOff->type = $request->type;
        $timeOff->start_date = $request->start_date;
        $timeOff->end_date = $request->end_date;
        $timeOff->days = $days;
        $timeOff->reason = $request->reason;

        if ($request->has('attachment_url')) {
            $timeOff->document_url = $request->attachment_url;
        }

        $timeOff->save();

        return response()->json([
            'message' => 'Time off request updated successfully',
            'data' => $timeOff
        ]);
    }

    /**
     * API: Cancel a time off request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function apiCancelTimeOff($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $timeOff = TimeOff::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$timeOff) {
            return response()->json([
                'message' => 'Time off request not found',
            ], 404);
        }

        // Only allow cancellation if the request is still pending
        if ($timeOff->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot cancel time off request that has been ' . $timeOff->status,
            ], 422);
        }

        $timeOff->delete();

        return response()->json([
            'message' => 'Time off request cancelled successfully'
        ]);
    }

    /**
     * API: Get user's leave balance
     *
     * @return \Illuminate\Http\Response
     */
    public function apiGetLeaveBalance()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $stats = $this->getLeaveStatistics($user->id);

        return response()->json([
            'message' => 'Leave balance retrieved successfully',
            'data' => $stats
        ]);
    }
}
