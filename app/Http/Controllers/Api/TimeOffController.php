<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeOff;

class TimeOffController extends Controller
{
    /**
     * Display a listing of the time-off requests.
     */
    public function index()
    {
        $timeOffs = TimeOff::all();
        return response()->json($timeOffs);
    }

    /**
     * Store a newly created time-off request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:cuti_tahunan,
                    izin_jam_kerja,
                    izin_sebelum_atau_sesudah_istirahat,
                    cuti_umroh,
                    cuti_haji,
                    dinas_dalam_kota,
                    dinas_luar_kota,
                    izin_tidak_masuk,
                    sakit_berkepanjangan_12_bulan_pertama,
                    sakit_berkepanjangan_4_bulan_pertama,
                    sakit_berkepanjangan_8_bulan_pertama,
                    sakit_berkepanjangan_diatas_12_bulan_pertama,
                    sakit_dengan_surat_dokter,
                    sakit_tanpa_surat_dokter,
                    cuti_menikah,
                    cuti_menikahkan_anak,
                    cuti_khitanan_anak,
                    cuti_istri_melahirkan_atau_keguguran,
                    cuti_keluarga_meninggal,
                    cuti_anggota_keluarga_dalam_satu_rumah_meninggal',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'document_url' => 'nullable|string',
        ]);

        $days = (new \DateTime($validated['start_date']))->diff(new \DateTime($validated['end_date']))->days + 1;

        $timeOff = TimeOff::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $days,
            'reason' => $validated['reason'],
            'document_url' => $validated['document_url'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json($timeOff, 201);
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

        return response()->json($timeOff);
    }

    /**
     * Display a single time-off request.
     */
    public function show($id)
    {
        $timeOff = TimeOff::findOrFail($id);
        return response()->json($timeOff);
    }

    /**
     * Remove a time-off request.
     */
    public function destroy($id)
    {
        $timeOff = TimeOff::findOrFail($id);
        $timeOff->delete();

        return response()->json(['message' => 'Time off deleted successfully']);
    }
}
