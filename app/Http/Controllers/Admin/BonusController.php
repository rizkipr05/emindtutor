<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sallary;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Staff;

class BonusController extends Controller
{
    /**
     * Tampilkan form tambah bonus/potongan untuk user tertentu.
     */
    public function createBonus($id)
    {
        // dd("masuk");
        // return view('admin.teachers.sallaries.create-bonus', compact('user'));

        // Coba cek dulu di teacher
        $teacher = Teacher::find($id);
        if ($teacher) {
            $user = $teacher->user;
            // dd("masuk teacher");
            // dd($user);
            return view('admin.teachers.sallaries.create-bonus', compact('user'));
        }

        // Kalau bukan teacher, cek di staff
        $staff = Staff::find($id);
        if ($staff) {
            // dd("masuk staff");
            $user = $staff->user; // pastikan relasi Staff -> User sudah ada
            return view('admin.teachers.sallaries.create-bonus', compact('user'));
        }

        abort(404, 'Data tidak ditemukan.');
    }

    /**
     * Simpan bonus / potongan gaji.
     */
    public function storeBonus(Request $request, User $user)
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $request->validate([
            'amount'      => 'required|numeric', // boleh negatif untuk potongan
            'description' => 'nullable|string',
            'month'       => 'required|string|in:' . implode(',', $months),
            'year'        => 'required|integer|min:2000',
        ]);

        $salaryData = [
            'teacher_id'        => null,
            'staff_id'          => null,
            'class_schedule_id' => null,
            'amount'            => $request->amount,
            'description'       => $request->description,
            'month'             => $request->month,
            'year'              => $request->year,
            'payment_date'      => null,
            'status'            => 'pending',
        ];

        if ($user->role === 'teacher') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if (! $teacher) {
                return back()->withErrors(['user' => 'Data teacher tidak ditemukan.']);
            }
            $salaryData['teacher_id'] = $teacher->id;
        } elseif ($user->role === 'admin') {
            $staff = \App\Models\Staff::where('user_id', $user->id)->first();
            if (! $staff) {
                return back()->withErrors(['user' => 'Data staff tidak ditemukan.']);
            }
            $salaryData['staff_id'] = $staff->id;
        } else {
            return back()->withErrors([
                'user' => 'Role user tidak valid untuk bonus/potongan.'
            ]);
        }

        Sallary::create($salaryData);

        return redirect()
            ->route('admin.teachers.sallaries.index', [
                'month' => $request->month,
                'year'  => $request->year
            ])
            ->with('success', 'Bonus / potongan gaji berhasil ditambahkan.');
    }


}