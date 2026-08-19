<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Company;
use App\Models\CompanyInvoice;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'individu'); // default individu
        $courses = Course::where('jenis', $jenis)
            ->where('company_id', Auth::user()->company->id)
            ->latest()
            ->get();

        return view('admin.courses.index', compact('courses', 'jenis'));
    }

    public function create()
    {
        $user = Auth::user();

        // Ambil company milik user
        $company = Company::where('user_id', $user->id)->firstOrFail();

        $today = now()->toDateString();

        // Ambil subscription aktif (hanya yang status active dan paid)
        $activeSubscription = CompanyInvoice::where('company_id', $company->id)
            ->where('status', 'active')
            ->where('status_bayaran', 'paid')
            ->whereDate('tarikh_terbit', '<=', $today)
            ->whereDate('tarikh_tamat', '>=', $today)
            ->latest()
            ->with('package')
            ->first();

        if (!$activeSubscription || !$activeSubscription->package) {
            return redirect()->route('a.dashboard.index')
                ->with('error', 'Anda tidak mempunyai langganan aktif untuk menambah kelas.');
        }

        // Hitung jumlah course milik company
        $currentCourses = Course::where('company_id', $company->id)->count();

        // Ambil batas maksimum kelas dari package (0 atau null berarti tanpa batas / unlimited)
        $maxClasses = $activeSubscription->package->maks_kelas;

        if ($maxClasses && $maxClasses > 0 && $currentCourses >= $maxClasses) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Jumlah kelas sudah mencapai maksimum (' . $maxClasses . ').');
        }

        // Jika masih boleh tambah
        return view('admin.courses.create', [
            'company' => $company,
        ]);
    }

    public function store(Request $request)
    {
        // dd("hi");
        $request->validate([
            'nama_kos_en' => 'required|string|max:255',
            'nama_kos_my' => 'required|string|max:255',
            'fee' => 'required|integer',
            'max_student' => 'integer',
            'teaching_mode' => 'required|in:offline,online,hybrid',
            'minimum_sessions' => 'nullable|integer',
            'sessions_duration' => 'nullable|integer',
            'description' => 'nullable|string',
            'instructions_template' => 'nullable|string',
            'session_notes_template' => 'nullable|string',
            'jenis' => 'required|in:individu,kumpulan',
            'registration_link' => 'nullable|url|max:255',
        ]);
        // dd($request->all());

        Course::create([
            'company_id' => Auth::user()->company->id,
            'nama_kos' => $request->nama_kos_my,
            'nama_kos_en' => $request->nama_kos_en,
            'nama_kos_my' => $request->nama_kos_my,
            'fee' => $request->fee,
            'max_student' => $request->max_student,
            'charge_on_registration' => $request->has('charge_on_registration'),
            'include_registration_fee' => $request->has('include_registration_fee'),
            'teaching_mode' => $request->teaching_mode,
            'minimum_sessions' => $request->minimum_sessions,
            'minimum_sessions_unit' => $request->minimum_sessions_unit,
            'sessions_duration' => $request->sessions_duration,
            'sessions_duration_unit' => $request->sessions_duration_unit,
            'description' => $request->description,
            'instructions_template' => $request->instructions_template,
            'session_notes_template' => $request->session_notes_template,
            'jenis' => $request->jenis,
            'registration_link' => $request->registration_link,
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        // dd($course);
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        // $this->authorize('update', $course);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // $this->authorize('update', $course);

        // dd($request->all());
        $request->validate([
            'nama_kos_en' => 'required|string|max:255',
            'nama_kos_my' => 'required|string|max:255',
            'fee' => 'required|integer',
            'charge_on_registration' => 'required|boolean',
            'include_registration_fee' => 'required|boolean',
            'teaching_mode' => 'required|in:offline,online,hybrid',
            'minimum_sessions' => 'nullable|integer',
            'minimum_sessions_unit' => 'nullable|in:session,hour,minute',
            'sessions_duration' => 'nullable|integer',
            'sessions_duration_unit' => 'required_with:sessions_duration|in:minute,hour',
            'description' => 'nullable|string',
            'instructions_template' => 'nullable|string',
            'jenis' => 'required|in:individu,kumpulan',
            'registration_link' => 'nullable|url|max:255',
        ]);


        $data = $request->all();
        $data['nama_kos'] = $request->nama_kos_my;
        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        // $this->authorize('delete', $course);
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
