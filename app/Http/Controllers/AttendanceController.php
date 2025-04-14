<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Tampilkan daftar kehadiran
    public function index(Request $request)
    {
        $attendances = Attendance::with('user')
            ->when($request->input('name'), function ($query, $name) {
                $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('pages.attendances.index', compact('attendances'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('pages.attendances.edit', compact('attendance'));
    }

    // Simpan perubahan data kehadiran
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'latlon_in' => $request->latlon_in,
            'latlon_out' => $request->latlon_out,
            'description' => $request->description,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    // Hapus data kehadiran
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Data kehadiran berhasil dihapus.');
    }
}
