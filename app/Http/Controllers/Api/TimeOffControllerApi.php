<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeOff;
use App\Models\Holiday;
use Carbon\Carbon;

class TimeOffControllerApi extends Controller
{
    /**
     * Display a listing of the time-off requests.
     */
    public function index()
    {
        $timeOffs = TimeOff::all();
        return response()->json([
            'status' => 'Success',
            'message' => 'Time offs retrieved successfully',
            'data' => $timeOffs
        ]);
    }

    /**
     * Store a newly created time-off request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:cuti_tahunan,izin_jam_kerja,izin_sebelum_atau_sesudah_istirahat,cuti_umroh,cuti_haji,dinas_dalam_kota,dinas_luar_kota,izin_tidak_masuk,sakit_berkepanjangan_12_bulan_pertama,sakit_berkepanjangan_4_bulan_pertama,sakit_berkepanjangan_8_bulan_pertama,sakit_berkepanjangan_diatas_12_bulan_pertama,sakit_dengan_surat_dokter,sakit_tanpa_surat_dokter,cuti_menikah,cuti_menikahkan_anak,cuti_khitanan_anak,cuti_istri_melahirkan_atau_keguguran,cuti_keluarga_meninggal,cuti_anggota_keluarga_dalam_satu_rumah_meninggal',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'document_url' => 'nullable|string',
        ]);

        // Calculate working days (excluding holidays)
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Check if the current date is a holiday
            $isHoliday = Holiday::whereDate('date', $currentDate->format('Y-m-d'))->exists();

            if (!$isHoliday) {
                $workingDays++;
            }

            $currentDate->addDay();
        }

        $timeOff = TimeOff::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $workingDays, // Use working days instead of calendar days
            'reason' => $validated['reason'],
            'document_url' => $validated['document_url'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Time off request created successfully',
            'data' => $timeOff,
            'working_days' => $workingDays,
            'calendar_days' => $endDate->diffInDays($startDate) + 1
        ], 201);
    }

    /**
     * Update the status of a time-off request.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $timeOff = TimeOff::findOrFail($id);
        $timeOff->status = $validated['status'];
        $timeOff->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Time off status updated successfully',
            'data' => $timeOff
        ]);
    }

    /**
     * Display a single time-off request.
     */
    public function show($id)
    {
        $timeOff = TimeOff::findOrFail($id);
        return response()->json([
            'status' => 'Success',
            'message' => 'Time off details retrieved successfully',
            'data' => $timeOff
        ]);
    }

    /**
     * Remove a time-off request.
     */
    public function destroy($id)
    {
        $timeOff = TimeOff::findOrFail($id);
        $timeOff->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'Time off deleted successfully'
        ]);
    }

    /**
     * Get time off requests for a specific user.
     */
    public function getUserTimeOffs(Request $request, $userId)
    {
        $timeOffs = TimeOff::where('user_id', $userId)->get();

        return response()->json([
            'status' => 'Success',
            'message' => 'User time offs retrieved successfully',
            'data' => $timeOffs
        ]);
    }

    /**
     * Calculate working days between two dates (excluding holidays).
     */
    public function calculateWorkingDays(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $workingDays = 0;
        $holidays = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Check if the current date is a holiday
            $holiday = Holiday::whereDate('date', $currentDate->format('Y-m-d'))->first();

            if ($holiday) {
                $holidays[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'name' => $holiday->name,
                    'type' => $holiday->type
                ];
            } else {
                $workingDays++;
            }

            $currentDate->addDay();
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Working days calculated successfully',
            'working_days' => $workingDays,
            'calendar_days' => $endDate->diffInDays($startDate) + 1,
            'holidays' => $holidays
        ]);
    }

    /**
     * Get time off requests by status.
     */
    public function getByStatus($status)
    {
        $validStatuses = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            return response()->json(['status' => 'Error', 'message' => 'Invalid status'], 400);
        }

        $timeOffs = TimeOff::where('status', $status)->get();

        return response()->json([
            'status' => 'Success',
            'message' => "Time offs with status {$status} retrieved successfully",
            'data' => $timeOffs
        ]);
    }
}
