<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company?->id;
        abort_unless($companyId, 404);

        $date = $request->input('date', now('Asia/Kuala_Lumpur')->toDateString());

        $attendances = StudentAttendance::with(['user', 'classSchedule.course'])
            ->whereHas('classSchedule', fn ($query) => $query->where('company_id', $companyId))
            ->when($date, fn ($query) => $query->whereDate('attendance_date', $date))
            ->orderByDesc('attendance_date')
            ->orderByDesc('attendance_time')
            ->paginate(30)
            ->withQueryString();

        return view('admin.student_attendances.index', compact('attendances', 'date'));
    }

    public function edit(StudentAttendance $attendance)
    {
        $this->authorizeAttendance($attendance);

        return view('admin.student_attendances.edit', compact('attendance'));
    }

    public function update(Request $request, StudentAttendance $attendance)
    {
        $this->authorizeAttendance($attendance);

        $data = $request->validate([
            'attendance_date' => ['required', 'date'],
            'attendance_time' => ['required', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:present,absent,late'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $attendance->update($data);

        return redirect()
            ->route('admin.student-attendances.index', ['date' => $data['attendance_date']])
            ->with('success', 'Rekod kehadiran pelajar berjaya dikemas kini.');
    }

    private function authorizeAttendance(StudentAttendance $attendance): void
    {
        abort_unless(
            $attendance->classSchedule()->where('company_id', auth()->user()->company?->id)->exists(),
            404
        );
    }
}
