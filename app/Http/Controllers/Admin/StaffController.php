<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company
            ?? Staff::with('company.user')->where('user_id', Auth::id())->first()?->company;

        if ($company) {
            $company->loadMissing('user');
        }

        $companyId = $company?->id;

        $staff = Staff::with(['user', 'company'])
            ->where('company_id', $companyId)
            ->get();

        return view('admin.staff.index', compact('staff', 'company'));
    }   

    public function create()
    {
        return view('admin.staff.create');
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
            'role' => 'admin',
        ]);

        // Buat staff baru
        Staff::create([
            'user_id' => $user->id,
            'company_id' => Auth::user()->company->id,
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

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function show(Staff $staff)
    {
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $users = User::all();
        $companies = Company::all();
        return view('admin.staff.edit', compact('staff', 'users', 'companies'));
    }

    public function update(Request $request, Staff $staff)
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

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        // Hapus user yang terkait dengan staff
        if ($staff->user) {
            $staff->user->delete();
        }

        // Hapus staff
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff dan user terkait berhasil dihapus.');
}

    public function bulkActivate(Request $request)
    {
        // dd($request->all());
        $ids = $request->input('item_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Sila pilih staff yang ingin diaktifkan.');
        }

        Staff::whereIn('id', $ids)->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Staff berjaya diaktifkan.');
    }

    public function bulkDeactivate(Request $request)
    {
        // dd($request->all());

        $ids = $request->input('item_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Sila pilih staff yang ingin dinyahaktifkan.');
        }

        Staff::whereIn('id', $ids)->update(['status' => 'inactive']);

        return redirect()->back()->with('success', 'Staff berjaya dinyahaktifkan.');
    }
}
