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
    // Daftar tipe cuti yang mengurangi jatah cuti
    private $leaveBalanceTypes = [
        'cuti_tahunan',
        'izin_tidak_masuk',
        'sakit_dengan_surat_dokter',
        'sakit_tanpa_surat_dokter'
    ];

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
            'type' => 'required|in:cuti_tahunan,izin_jam_kerja,izin_sebelum_atau_sesudah_istirahat,cuti_umroh,cuti_haji,dinas_dalam_kota,dinas_luar_kota,izin_tidak_masuk,sakit_berkepanjangan_12_bulan_pertama,sakit_berkepanjangan_4_bulan_pertama,sakit_berkepanjangan_8_bulan_pertama,sakit_berkepanjangan_diatas_12_bulan_pertama,sakit_dengan_surat_dokter,sakit_tanpa_surat_dokter,cuti_menikah,cuti_menikahkan_anak,cuti_khitanan_anak,cuti_istri_melahirkan_atau_keguguran,cuti_keluarga_meninggal,cuti_anggota_keluarga_dalam_satu_rumah_meninggal',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'document' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Calculate number of days (fixed calculation)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Include start and end date in the count (end - start + 1)
        $days = $startDate->diffInDays($endDate->copy()->startOfDay()) + 1;

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

        // If approved and type reduces leave balance, update user's leave balance
        if ($request->status === 'approved' && in_array($request->type, $this->leaveBalanceTypes)) {
            $user = User::find($request->user_id);
            if ($user && $user->leave_balance >= $days) {
                $user->leave_balance -= $days;
                $user->save();
            }
        }

        // Validate start date and end date
        if ($endDate->lt($startDate)) {
            return response()->json([
                'message' => 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai'
            ], 422);
        }


        // For sick leave, create attendance records if approved
        if (($request->type === 'sakit_dengan_surat_dokter' || $request->type === 'sakit_tanpa_surat_dokter') && $request->status === 'approved') {
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
            'type' => 'required|in:cuti_tahunan,izin_jam_kerja,izin_sebelum_atau_sesudah_istirahat,cuti_umroh,cuti_haji,dinas_dalam_kota,dinas_luar_kota,izin_tidak_masuk,sakit_berkepanjangan_12_bulan_pertama,sakit_berkepanjangan_4_bulan_pertama,sakit_berkepanjangan_8_bulan_pertama,sakit_berkepanjangan_diatas_12_bulan_pertama,sakit_dengan_surat_dokter,sakit_tanpa_surat_dokter,cuti_menikah,cuti_menikahkan_anak,cuti_khitanan_anak,cuti_istri_melahirkan_atau_keguguran,cuti_keluarga_meninggal,cuti_anggota_keluarga_dalam_satu_rumah_meninggal',
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
        $oldReducesBalance = in_array($oldType, $this->leaveBalanceTypes);
        $newReducesBalance = in_array($request->type, $this->leaveBalanceTypes);

        // Calculate number of days (fixed calculation)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Include start and end date in the count (end - start + 1)
        $days = $startDate->diffInDays($endDate->copy()->startOfDay()) + 1;

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

        // Handle leave balance updates
        $user = User::find($timeOff->user_id);
        if ($user) {
            // If was approved and reduced balance, but now rejected/pending, restore balance
            if ($statusChanged && $oldStatus === 'approved' && $request->status !== 'approved' && $oldReducesBalance) {
                $user->leave_balance += $oldDays;
                $user->save();
            }

            // If newly approved and reduces balance, deduct from balance
            if ($statusChanged && $request->status === 'approved' && $newReducesBalance) {
                if ($user->leave_balance >= $days) {
                    $user->leave_balance -= $days;
                    $user->save();
                }
            }

            // If approved, type changed, and balance impact changed
            if (!$statusChanged && $request->status === 'approved' && $oldType !== $request->type) {
                // If old type reduced balance but new doesn't, restore balance
                if ($oldReducesBalance && !$newReducesBalance) {
                    $user->leave_balance += $oldDays;
                    $user->save();
                }
                // If old type didn't reduce balance but new does, deduct balance
                else if (!$oldReducesBalance && $newReducesBalance) {
                    if ($user->leave_balance >= $days) {
                        $user->leave_balance -= $days;
                        $user->save();
                    }
                }
                // If both reduce balance but days changed, adjust the difference
                else if ($oldReducesBalance && $newReducesBalance && $oldDays !== $days) {
                    $difference = $oldDays - $days;
                    $user->leave_balance += $difference;
                    $user->save();
                }
            }

            // If approved, same type that reduces balance, but days changed
            if (!$statusChanged && $request->status === 'approved' && $oldType === $request->type && $newReducesBalance && $oldDays !== $days) {
                $difference = $oldDays - $days;
                $user->leave_balance += $difference;
                $user->save();
            }
        }

        // For sick leave, create attendance records if newly approved
        if (($timeOff->type === 'sakit_dengan_surat_dokter' || $timeOff->type === 'sakit_tanpa_surat_dokter') && $statusChanged && $request->status === 'approved') {
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

        // If approved and type reduces leave balance, restore leave balance
        if ($timeOff->status === 'approved' && in_array($timeOff->type, $this->leaveBalanceTypes)) {
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

            // If type reduces leave balance, update user's leave balance
            if (in_array($timeOff->type, $this->leaveBalanceTypes)) {
                $user = User::find($timeOff->user_id);
                if ($user && $user->leave_balance >= $timeOff->days) {
                    $user->leave_balance -= $timeOff->days;
                    $user->save();
                }
            }

            // For sick leave, create attendance records
            if ($timeOff->type === 'sakit_dengan_surat_dokter' || $timeOff->type === 'sakit_tanpa_surat_dokter') {
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

        // If it was approved and type reduces leave balance, restore leave balance
        if ($timeOff->status === 'approved' && in_array($timeOff->type, $this->leaveBalanceTypes)) {
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

        // Include start and end day in the count
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

        // Default values - total jatah cuti adalah 13 hari
        $totalLeaveAllowance = 13;

        $stats = [
            'totalAllowance' => $totalLeaveAllowance,
            'leaveUsed' => 0,
            'leaveRemaining' => $totalLeaveAllowance,
            'leaveTypes' => []
        ];

        if ($userId) {
            $user = User::find($userId);

            if ($user) {
                // Get current year
                $currentYear = Carbon::now()->year;

                // Get all approved time offs that reduce leave balance
                $leaveUsed = TimeOff::where('user_id', $userId)
                    ->whereIn('type', $this->leaveBalanceTypes)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->get();

                // Calculate total used days
                $totalUsed = $leaveUsed->sum('days');

                // Group by type
                $leaveByType = [];
                foreach ($this->leaveBalanceTypes as $type) {
                    $used = $leaveUsed->where('type', $type)->sum('days');
                    if ($used > 0) {
                        $leaveByType[$type] = $used;
                    }
                }

                // Update stats - sisa cuti adalah leave_balance dari database
                $stats['leaveRemaining'] = $user->leave_balance;
                $stats['leaveUsed'] = $totalLeaveAllowance - $user->leave_balance;
                $stats['leaveTypes'] = $leaveByType;
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
            'type' => 'required|in:cuti_tahunan,izin_jam_kerja,izin_sebelum_atau_sesudah_istirahat,cuti_umroh,cuti_haji,dinas_dalam_kota,dinas_luar_kota,izin_tidak_masuk,sakit_berkepanjangan_12_bulan_pertama,sakit_berkepanjangan_4_bulan_pertama,sakit_berkepanjangan_8_bulan_pertama,sakit_berkepanjangan_diatas_12_bulan_pertama,sakit_dengan_surat_dokter,sakit_tanpa_surat_dokter,cuti_menikah,cuti_menikahkan_anak,cuti_khitanan_anak,cuti_istri_melahirkan_atau_keguguran,cuti_keluarga_meninggal,cuti_anggota_keluarga_dalam_satu_rumah_meninggal',
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

        // Calculate number of days (fixed calculation)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Include start and end date in the count (end - start + 1)
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
            'type' => 'required|in:cuti_tahunan,izin_jam_kerja,izin_sebelum_atau_sesudah_istirahat,cuti_umroh,cuti_haji,dinas_dalam_kota,dinas_luar_kota,izin_tidak_masuk,sakit_berkepanjangan_12_bulan_pertama,sakit_berkepanjangan_4_bulan_pertama,sakit_berkepanjangan_8_bulan_pertama,sakit_berkepanjangan_diatas_12_bulan_pertama,sakit_dengan_surat_dokter,sakit_tanpa_surat_dokter,cuti_menikah,cuti_menikahkan_anak,cuti_khitanan_anak,cuti_istri_melahirkan_atau_keguguran,cuti_keluarga_meninggal,cuti_anggota_keluarga_dalam_satu_rumah_meninggal',
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

        // Calculate number of days (fixed calculation)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Include start and end date in the count (end - start + 1)
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
