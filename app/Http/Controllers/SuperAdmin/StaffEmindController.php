<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StaffEmind;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffEmindController extends Controller
{
    public function index()
    {
        $staff = StaffEmind::with('user')
                    ->whereHas('user', function($q) {
                        $q->where('role', 'super admin');
                    })
                    ->get();
        
        $logs = ActivityLog::with('user')->latest()->paginate(20);

        return view('super-admin.staff.index', compact('staff', 'logs'));
    }   

    public function create()
    {
        return view('super-admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'ic_number' => 'required|string|max:255|unique:staff,ic_number',
            'phone' => 'nullable|string|max:20',
            'allowance' => 'required|numeric',
            'allowance_type' => 'required|in:per_hour,session,per_month',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_holder' => 'required|string|max:255',
            'account_holders_ic_number' => 'nullable|string|max:255',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->ic_number), // default password = IC Number
            'role' => 'super admin',
        ]);

        // Buat staff baru
        StaffEmind::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'ic_number' => $request->ic_number,
            'allowance' => $request->allowance,
            'allowance_type' => $request->allowance_type,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'account_holders_ic_number' => $request->account_holders_ic_number,
            'status' => 'active',
        ]);

        return redirect()->route('superadmin.staff.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function show(StaffEmind $staff)
    {
        return view('super-admin.staff.show', compact('staff'));
    }

    public function edit(StaffEmind $staff)
    {
        $users = User::all();
        return view('super-admin.staff.edit', compact('staff', 'users'));
    }

    public function update(Request $request, StaffEmind $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->user_id,
            'phone' => 'required|string|max:20',
            'ic_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:100',
            'bank_account_holder' => 'required|string|max:100',
            'account_holders_ic_number' => 'nullable|string|max:20',
            'allowance' => 'required|string|max:100',
            'allowance_type' => 'required|in:per_hour,session,per_month',
        ]);

        // Update User
        $user = $staff->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->ic_number); // reset password ke ic_number
        $user->save();

        // Update Staff
        $staff->update([
            'phone' => $request->phone,
            'ic_number' => $request->ic_number,
            'allowance' => $request->allowance,
            'allowance_type' => $request->allowance_type,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'account_holders_ic_number' => $request->account_holders_ic_number,
        ]);

        return redirect()->route('superadmin.staff.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(StaffEmind $staff)
    {
        $staff->delete();
        return redirect()->route('superadmin.staff.index')->with('success', 'Staff berhasil dihapus.');
    }
}
