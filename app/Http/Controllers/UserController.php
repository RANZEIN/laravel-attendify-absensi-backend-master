<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\TimeOff;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Daftar tipe cuti yang mengurangi jatah cuti
    private $leaveBalanceTypes = [
        'cuti_tahunan',
        'izin_tidak_masuk',
        'sakit_dengan_surat_dokter',
        'sakit_tanpa_surat_dokter'
    ];

    //index
    public function index()
    {
        //search by name, pagination 10
        $users = User::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Hitung jatah cuti untuk setiap user
        foreach ($users as $user) {
            $user->leave_stats = $this->calculateLeaveBalance($user->id);
        }

        return view('pages.users.index', compact('users'));
    }

    //create
    public function create()
    {
        return view('pages.users.create');
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'department' => $request->department,
            'leave_balance' => 13, // Default leave balance adalah 13 hari
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    //edit
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    //update
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'position' => $request->position,
            'department' => $request->department,
        ]);

        //if password filled
        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    //destroy
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    //show
    public function show(User $user)
    {
        $leaveStats = $this->calculateLeaveBalance($user->id);

        return view('pages.users.show', compact('user', 'leaveStats'));
    }

    /**
     * Menghitung jatah cuti yang tersisa untuk user
     *
     * @param int $userId
     * @return array
     */
    private function calculateLeaveBalance($userId)
    {
        // Total jatah cuti adalah 13 hari
        $totalAllowance = 13;

        // Default stats
        $stats = [
            'totalAllowance' => $totalAllowance,
            'leaveUsed' => 0,
            'leaveRemaining' => $totalAllowance,
            'leaveTypes' => []
        ];

        $user = User::find($userId);

        if ($user) {
            // Get current year
            $currentYear = Carbon::now()->year;

            // Debug: Tampilkan query yang akan dijalankan
            $query = TimeOff::where('user_id', $userId)
                ->whereIn('type', $this->leaveBalanceTypes)
                ->where('status', 'approved')
                ->whereYear('start_date', $currentYear);

            // Ambil semua cuti yang disetujui dan mengurangi jatah cuti
            $leaveUsed = $query->get();

            // Hitung total hari cuti yang digunakan
            // Pastikan nilai days selalu positif dengan menggunakan abs()
            $totalUsed = 0;
            foreach ($leaveUsed as $leave) {
                // Pastikan nilai days adalah positif
                $days = abs($leave->days);
                $totalUsed += $days;
            }

            // Kelompokkan berdasarkan tipe cuti
            $leaveByType = [];
            foreach ($this->leaveBalanceTypes as $type) {
                $typeLeaves = $leaveUsed->where('type', $type);
                $used = 0;
                foreach ($typeLeaves as $leave) {
                    $used += abs($leave->days);
                }

                if ($used > 0) {
                    $leaveByType[$type] = $used;
                }
            }

            // Hitung sisa jatah cuti (13 - total yang digunakan)
            $remaining = $totalAllowance - $totalUsed;
            if ($remaining < 0) $remaining = 0;

            // Update stats
            $stats['leaveUsed'] = $totalUsed;
            $stats['leaveRemaining'] = $remaining;
            $stats['leaveTypes'] = $leaveByType;
        }

        return $stats;
    }
}
