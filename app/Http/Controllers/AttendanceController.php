<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Index
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

        return view('pages.absensi.index', compact('attendances'));
    }

    // Edit
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('pages.absensi.edit', compact('attendance'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'time_in' => 'required',
            'time_out' => 'required',
            'latlon_in' => 'required',
            'latlon_out' => 'required',
        ]);

        $attendance->update([
            'date' => $request->date,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'latlon_in' => $request->latlon_in,
            'latlon_out' => $request->latlon_out,
        ]);

        return redirect()->route('attendances.show', $id) // Pastikan ini ada di route
            ->with('success', 'Attendance updated successfully');
    }

    // Show
    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('pages.absensi.show', compact('attendance'));
    }

    // Destroy
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance deleted successfully');
    }
}
