<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherAttendance;
use App\Models\ClassSchedule;

class TeacherAttendanceController extends Controller
{
    public function index()
    {
        $attendances = TeacherAttendance::with('teacher', 'classSchedule')->get();
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $schedules = ClassSchedule::where('status', 'akan_datang')->get();
        return view('attendances.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
            'note' => 'nullable|string',
            'class_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('class_photo')) {
            $data['class_photo'] = $request->file('class_photo')->store('class_photos', 'public');
        }

        TeacherAttendance::create($data);

        return redirect()->route('attendances.index')->with('success', 'Absensi berhasil disimpan.');
    }

    public function edit(TeacherAttendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }

    public function update(Request $request, TeacherAttendance $attendance)
    {
        $request->validate([
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
            'note' => 'nullable|string',
            'class_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('class_photo')) {
            $data['class_photo'] = $request->file('class_photo')->store('class_photos', 'public');
        }

        $attendance->update($data);

        return redirect()->route('attendances.index')->with('success', 'Absensi diperbarui.');
    }

    public function destroy(TeacherAttendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Data absensi dihapus.');
    }
}
