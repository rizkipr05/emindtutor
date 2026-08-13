<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use App\Models\TeachingReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachingReportController extends Controller
{
    public function create(Request $request, ClassSchedule $classSchedule)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        abort_unless($classSchedule->teacher_id === $teacher->id, 403);

        $classDate = Carbon::parse($request->input('date', now('Asia/Kuala_Lumpur')->toDateString()));
        $students = $classSchedule->students()->with('user')->get();
        $attendance = StudentAttendance::where('class_schedule_id', $classSchedule->id)
            ->whereDate('attendance_date', $classDate)
            ->get()
            ->keyBy('user_id');
        $reports = TeachingReport::where('class_schedule_id', $classSchedule->id)
            ->whereDate('class_date', $classDate)
            ->get()
            ->keyBy('student_id');

        return view('teacher.teaching_reports.form', compact('classSchedule', 'classDate', 'students', 'attendance', 'reports'));
    }

    public function store(Request $request, ClassSchedule $classSchedule)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        abort_unless($classSchedule->teacher_id === $teacher->id, 403);

        $validated = $request->validate([
            'class_date' => 'required|date',
            'reports' => 'required|array|min:1',
            'reports.*.learning_topic' => 'required|string|max:255',
            'reports.*.teacher_note' => 'nullable|string|max:3000',
        ]);

        $students = $classSchedule->students()->get()->keyBy('id');
        foreach ($validated['reports'] as $studentId => $data) {
            $student = $students->get((int) $studentId);
            abort_unless($student, 422);

            $attendanceStatus = StudentAttendance::where('class_schedule_id', $classSchedule->id)
                ->where('user_id', $student->user_id)
                ->whereDate('attendance_date', $validated['class_date'])
                ->value('status');

            TeachingReport::updateOrCreate(
                ['class_schedule_id' => $classSchedule->id, 'student_id' => $student->id, 'class_date' => $validated['class_date']],
                [
                    'company_id' => $classSchedule->company_id,
                    'teacher_id' => $teacher->id,
                    'learning_topic' => $data['learning_topic'],
                    'teacher_note' => $data['teacher_note'] ?? null,
                    'attendance_status' => $attendanceStatus,
                ]
            );
        }

        return redirect()->route('teacher.teaching-reports.create', ['classSchedule' => $classSchedule, 'date' => $validated['class_date']])
            ->with('success', __('reports.teaching.saved'));
    }
}
