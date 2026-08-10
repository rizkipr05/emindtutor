<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Broadcast;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Announcement;
use App\Mail\AnnouncementEmail;
use Illuminate\Support\Facades\Mail;


class CommunicationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Announcements yang dikirim oleh user ini
        $sentAnnouncements = Announcement::where('created_by', $user->id)
                                ->latest()
                                ->get();

        // Announcements yang diterima oleh user (kalau bukan super admin)
        $receivedAnnouncements = collect();
        if ($user->role !== 'super admin') {
            $receivedAnnouncements = $user->announcements()->latest()->get();
        }


        return view('student.communication.index', compact('sentAnnouncements', 'receivedAnnouncements'));
    }

    public function show($id)
    {
        $user = auth()->user();

        // ambil announcement + data creator
        $announcement = Announcement::with('creator')->findOrFail($id);

        $isCreator = (int) $announcement->created_by === (int) $user->id;
        $isRecipient = $announcement->users()->where('users.id', $user->id)->exists();

        if (!$isCreator && !$isRecipient) {
            abort(403, 'Anda tidak mempunyai akses ke pengumuman ini.');
        }

        // update is_read jika user ini termasuk penerima
        if ($isRecipient) {
            $announcement->users()
                ->updateExistingPivot($user->id, ['is_read' => true]);
        }

        return view('student.communication.show', compact('announcement'));
    }

    // MessageController.php
    public function create()
    {
        $user = auth()->user();

        abort_if($user->role === 'teacher', 403, 'Guru hanya dapat menerima pengumuman dari perusahaan.');

        // Default kosong
        $teachers = collect();
        $students = collect();
        $admins   = collect();

        if ($user->role === 'student') {
            $student = $user->students; // relasi ke student

            // Ambil company dari student
            $company = $student->company;

            // Kumpulin semua admin
            $companyAdmins = collect();

            // User utama dari company
            if ($company && $company->user) {
                $companyAdmins->push($company->user);
            }

            // Semua staff dari company
            $staffAdmins = Staff::where('company_id', $company->id ?? null)
                ->with('user')
                ->get()
                ->pluck('user');

            $admins = $companyAdmins->merge($staffAdmins);

            // Semua teacher
            $teachers = Teacher::with('user')->get()->pluck('user');

            // return view('messages.create', compact('admins', 'teachers'));
        }

        if ($user->role === 'teacher') {
            // cari company_id si admin
            $companyId = $user->teachers->company_id;
            // dd($companyId);

            // Ambil company dari student
            $company = $user->teachers->company;

            // ambil semua student di company ini
            $students = Student::where('company_id', $companyId)
                ->with('user') // supaya bisa akses name dari user
                ->get();
            
            // Kumpulin semua admin
            $companyAdmins = collect();

            // User utama dari company
            if ($company && $company->user) {
                $companyAdmins->push($company->user);
            }

            // Semua staff dari company
            $staffAdmins = Staff::where('company_id', $company->id ?? null)
                ->with('user')
                ->get()
                ->pluck('user');

            $admins = $companyAdmins->merge($staffAdmins);
        }

        if ($user->role === 'admin') {
            // cari company_id si admin
            $companyId = null;
            if ($user->company) {
                $companyId = $user->company->id;
            } else {
                $staff = Staff::where('user_id', $user->id)->first();
                if ($staff) {
                    $companyId = $staff->company_id;
                }
            }

            // ambil semua student di company ini
            $students = Student::where('company_id', $companyId)
                ->with('user') // supaya bisa akses name dari user
                ->get();

            // ambil semua teacher di company ini
            $teachers = Teacher::where('company_id', $companyId)
                ->with('user')
                ->get();
        }


        return view('student.communication.create', compact('teachers', 'students', 'admins'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->role === 'teacher', 403, 'Guru tidak diizinkan membuat pengumuman.');

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_audience' => 'required|in:all,companies,teachers,students',
            'redirect_route' => 'nullable|string|in:broadcasts.index,t.dashboard.index',
        ]);

        $user = auth()->user();

        $targetUsers = collect();

        // 1️⃣ Jika Super Admin
        if ($user->role === 'super admin') {
            if ($request->target_audience === 'companies') {
                $targetUsers = Company::pluck('user_id');
            }
        } 
        // 2️⃣ Jika Admin Company
        else {
            $companyId = null;

            // Cek apakah user adalah admin dari company langsung
            $company = Company::where('user_id', $user->id)->first();
            if ($company) {
                $companyId = $company->id;
            } else {
                // Jika bukan, cek di staff
                $staff = Staff::where('user_id', $user->id)->first();
                if ($staff) {
                    $companyId = $staff->company_id;
                }
            }

            // Jika guru, ambil company dari profil guru
            if (!$companyId && $user->role === 'teacher' && $user->teachers) {
                $companyId = $user->teachers->company_id;
            }

            if ($companyId) {
                // Kirim ke teachers
                if (in_array($request->target_audience, ['teachers', 'all'])) {
                    $teacherIds = Teacher::where('company_id', $companyId)->pluck('user_id');
                    $targetUsers = $targetUsers->merge($teacherIds);
                }

                // Kirim ke students
                if (in_array($request->target_audience, ['students', 'all'])) {
                    $studentIds = Student::where('company_id', $companyId)->pluck('user_id');
                    $targetUsers = $targetUsers->merge($studentIds);
                }
            }
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'target_audience' => $request->target_audience,
            'created_by' => $user->id,
        ]);

        // Ambil data user lengkap (termasuk email dan nama)
        $usersToEmail = User::whereIn('id', $targetUsers->unique())->get();

        foreach ($usersToEmail as $recipient) {
            // Simpan ke pivot tanpa membuat penerima dobel.
            $announcement->users()->syncWithoutDetaching([
                $recipient->id => ['is_read' => false],
            ]);

            // Kirim email dengan data pengirim ($user) dan penerima ($recipient)
            try {
                Mail::to($recipient->email)->send(new AnnouncementEmail($announcement, $user, $recipient));
            } catch (\Exception $e) {
                \Log::error("Gagal kirim ke: " . $recipient->email);
            }
        }

        // // Simpan semua target ke pivot announcement_user
        // foreach ($targetUsers->unique() as $targetUserId) {
        //     $announcement->users()->attach($targetUserId, ['is_read' => false]);
        // }

        // // Ambil data user lengkap berdasarkan ID yang sudah dikumpulkan
        // $usersToEmail = User::whereIn('id', $targetUsers->unique())->get();

        // foreach ($usersToEmail as $recipient) {
        //     try {
        //         Mail::to($recipient->email)->send(new AnnouncementEmail($announcement));
        //     } catch (\Exception $e) {
        //         \Log::error("Failed to send announcement email to: " . $recipient->email);
        //     }
        // }

        $redirectRoute = $request->input('redirect_route', 'broadcasts.index');

        return redirect()->route($redirectRoute)->with('success', 'Pengumuman berhasil dikirim.');
    }

}
