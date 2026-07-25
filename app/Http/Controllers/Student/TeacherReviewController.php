<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherReview;
use App\Models\ClassSchedule;
use App\Models\ClassScheduleStudent;
use App\Models\StudentInvoice;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Review;

class TeacherReviewController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('s.dashboard.index')
                ->with('error', 'Student profile not found.');
        }
        
        // Get reviews yang sudah dibuat oleh student ini
        $existingReviews = Review::where('student_id', $student->id)
            ->with(['teacher.user', 'course', 'classSchedule'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get semua guru yang terlibat dengan student ini melalui class_schedule
        $availableTeachers = ClassScheduleStudent::where('student_id', $student->id)
            ->with(['classSchedule.teacher.user', 'classSchedule.course'])
            ->get()
            ->filter(fn ($item) => $item->classSchedule && $item->classSchedule->teacher && $item->classSchedule->course)
            ->map(function ($item) {
                return (object) [
                    'teacher_id' => $item->classSchedule->teacher->id,
                    'teacher_name' => $item->classSchedule->teacher->user->name,
                    'course_id' => $item->classSchedule->course_id,
                    'course_name' => $item->classSchedule->course->nama_kos,
                    'nama_kelas' => $item->classSchedule->nama_kelas,
                    'class_schedule_id' => $item->classSchedule->id
                ];
            })
            ->unique('class_schedule_id');
        // dd($existingReviews->count());
        return view('student.reviews.index', compact('existingReviews', 'availableTeachers'));
    }

    public function create(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('s.dashboard.index')
                ->with('error', 'Student profile not found.');
        }

        $classSchedule = ClassSchedule::with(['teacher.user', 'course'])
            ->whereHas('classScheduleStudent', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->findOrFail($request->get('class_schedule_id'));
        
        $teacher = $classSchedule->teacher;
        $course = $classSchedule->course;
        
        return view('student.reviews.create', compact('teacher', 'course', 'classSchedule'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('s.dashboard.index')
                ->with('error', 'Student profile not found.');
        }

        $classSchedule = ClassSchedule::whereHas('classScheduleStudent', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->findOrFail($request->class_schedule_id);
        
        Review::create([
            'student_id' => $student->id,
            'teacher_id' => $classSchedule->teacher_id,
            'course_id' => $classSchedule->course_id,
            'class_schedule_id' => $classSchedule->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        
        return redirect()->route('student.reviews.index')
            ->with('success', 'Review berhasil diberikan!');
    }

    /**
     * Hapus review
     */
    public function destroy($invoiceId)
    {
        $studentId = Auth::id();

        $review = TeacherReview::where('student_invoice_id', $invoiceId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $review->delete();

        return redirect()->back()->with('success', 'Review berhasil dihapus.');
    }
}
