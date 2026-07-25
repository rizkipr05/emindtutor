<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Announcement;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::with(['user', 'company.user'])
            ->where('user_id', $user->id)
            ->first();
        $profileMissing = false;

        if (!$student) {
            $profileMissing = true;
            $student = new Student(['user_id' => $user->id]);
            $student->setRelation('user', $user);
            $courses = collect();
        } else {
            $courses = $student->invoices()
                ->where('status_bayaran', 'paid') // Only show courses from paid invoices
                ->with('course')
                ->get()
                ->pluck('course')
                ->filter()
                ->unique('id');
        }

        $announcements = Announcement::with('creator')
            ->whereHas('users', function ($recipientQuery) use ($user) {
                $recipientQuery->where('users.id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();
        $overview = [
            'announcements' => $announcements->count(),
            'courses' => $courses->count(),
            'payments' => $student ? $student->invoices()->where('status_bayaran', 'paid')->count() : 0,
            'paidAccess' => $student ? $student->invoices()->where('status_bayaran', 'paid')->whereNotNull('course_id')->count() : 0,
        ];

        return view('student.dashboard.index', compact('student', 'courses', 'announcements', 'profileMissing', 'overview'));
    }
}
