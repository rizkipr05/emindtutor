<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceTagihanMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Company;
use App\Models\Teacher;
use App\Models\ClassSchedule;
use App\Models\ClassScheduleDay;
use App\Models\ClassScheduleStudent;
use App\Models\User;
use App\Models\Course;
use App\Models\CompanyInvoice;
use App\Models\StudentInvoice;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\HeadingRowFormatter;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $companyId = Auth::user()->company?->id;
        abort_unless($companyId, 403);

        $students = Student::query()
            ->where('company_id', $companyId)
            ->with([
                'user',
                'invoices' => fn ($query) => $query->with('course')->latest('tarikh_terbit'),
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('ic_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('approval'), fn ($query) => $query->where('is_approved', $request->input('approval')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function sendSubscriptionNotification($studentId, $invoiceId)
    {
        $companyId = Auth::user()->company?->id;
        abort_unless($companyId, 403);

        $student = Student::where('company_id', $companyId)->with('user')->findOrFail($studentId);
        $invoice = StudentInvoice::with(['student.user', 'course'])
            ->where('student_id', $student->id)
            ->whereIn('status_bayaran', ['upcoming', 'overdue'])
            ->findOrFail($invoiceId);

        if (blank($student->user?->email)) {
            return back()->with('error', 'Email pelajar tidak tersedia.');
        }

        try {
            Mail::to($student->user->email)->send(new InvoiceTagihanMail($invoice));

            Log::info('Student subscription notification sent.', [
                'student_id' => $student->id,
                'invoice_id' => $invoice->id,
                'recipient' => $student->user->email,
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Student subscription notification failed.', [
                'student_id' => $student->id,
                'invoice_id' => $invoice->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Notifikasi langganan gagal dihantar. Sila semak konfigurasi email.');
        }

        return back()->with('success', 'Notifikasi langganan telah dihantar ke email pelajar.');
    }

    public function pending(Request $request)
    {
        $search = $request->input('search');
        $companyId = Auth::user()->company->id;

        // Ambil invoice + relasi student, user, course, dan payment
        $invoiceQuery = StudentInvoice::with([
                'student.user',
                'course',
                'payment' => function ($q) {
                    // hanya ambil payment yang dibuat dalam 7 hari terakhir
                    $q->where('created_at', '>=', now()->subDays(7));
                }
            ])
            ->where('status_bayaran', 'upcoming')
            ->whereHas('student', function ($q) use ($companyId) {
                $q->where('company_id', $companyId)
                ->where('is_approved', 'no');
            });
        // dd($invoiceQuery);

        // Search opsional
        if ($search) {
            $invoiceQuery->where(function ($q) use ($search) {
                $q->where('nomor_invoice', 'like', "%{$search}%")
                ->orWhereHas('student.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('student', function ($q2) use ($search) {
                    $q2->where('ic_number', 'like', "%{$search}%");
                })
                ->orWhereHas('course', function ($q2) use ($search) {
                    $q2->where('nama_kos', 'like', "%{$search}%");
                });
            });
        }

        $invoices = $invoiceQuery->get();

        // Filter: hanya invoice yang belum punya class untuk course tsb
        $pendingInvoices = $invoices->filter(function ($invoice) {
            return !\App\Models\ClassScheduleStudent::where('student_id', $invoice->student_id)
                ->whereHas('classSchedule', function ($q) use ($invoice) {
                    $q->where('course_id', $invoice->course_id);
                })->exists();
        })->values();

        // Paginate collection
        $perPage = 10;
        $page = (int) $request->get('page', 1);
        $total = $pendingInvoices->count();
        $pagedData = $pendingInvoices->forPage($page, $perPage);

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $total,
            $perPage,
            $page,
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );

        // Class ready yg bisa dipilih admin
        $classSchedules = ClassSchedule::where('company_id', $companyId)
                            ->where('status', 'akan_datang')
                            ->get();

        return view('admin.students.pending', [
            'students' => $paginated, // item tiap invoice sudah termasuk payment
            'search' => $search,
            'classSchedules' => $classSchedules,
        ]);
    }



    // Tampilkan student SUDAH di-approve
    // public function approved(Request $request)
    // {
    //     $search = $request->input('search');

    //     $companyId = Auth::user()->company->id;

    //     $students = Student::where('is_approved', 'yes')
    //         ->where('company_id', $companyId)
    //         // ->whereHas('invoices', function ($q) {
    //         //     $q->where('status_bayaran', 'paid')
    //         //     ->whereDate('tarikh_tamat', '>=', now());
    //         // })
    //         // ->when($search, function ($query, $search) {
    //         //     $query->where('ic_number', 'like', "%$search%")
    //         //         ->orWhereHas('user', function ($query) use ($search) {
    //         //             $query->where('name', 'like', "%$search%");
    //         //         });
    //         // })
    //         // ->with(['user', 'invoices' => function ($q) {
    //         //     $q->where('status_bayaran', 'paid')
    //         //     ->whereDate('tarikh_tamat', '>=', now());
    //         // }])
    //         ->paginate(10);

    //     // dd($students);

    //     return view('admin.students.approved', compact('students', 'search'));
    // }

public function approved(Request $request)
{
    $search = $request->input('search');
    $companyId = Auth::user()->company->id;
    $today = Carbon::now();
    $todayStr = $today->format('dmY');

    $students = Student::where('company_id', $companyId)
        ->where('is_approved', 'yes')
        ->with('user')
        ->when($search, function ($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('ic_number', 'like', "%$search%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        })
        ->get(); // WAJIB GET supaya AlpineJS bisa akses semua data

    // 2. Loop untuk cek masa berlaku (Subscription)
    foreach ($students as $student) {
        if ($student->sub_end && Carbon::parse($student->sub_end)->isPast() && $student->status == 'paid') {
            
            // 1. Ambil data invoice terakhir untuk dapatkan course_id dan fee
            $lastInvoice = StudentInvoice::where('student_id', $student->id)
                ->latest()
                ->first();

            $courseId = $lastInvoice ? $lastInvoice->course_id : $student->course_id;
            $amount = $lastInvoice ? $lastInvoice->jumlah : ($student->course->fee ?? 0);

            // 2. Generate Nomor Invoice (Auto Sequence)
            // Kita hitung jumlah invoice yang dibuat HARI INI untuk company ini
            $countToday = StudentInvoice::whereDate('created_at', $today->toDateString())
                ->whereHas('student', function($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })->count() + 1;

            $sequence = str_pad($countToday, 4, '0', STR_PAD_LEFT);
            $invoiceId = "EMT-{$todayStr}-{$companyId}{$sequence}";

            // 3. Update status student ke pending
            $student->update([
                'status' => 'pending' 
            ]);

            // 4. Buat invoice baru
            StudentInvoice::create([
                'student_id'    => $student->id,
                'course_id'     => $courseId,
                'nomor_invoice' => $invoiceId,
                'jumlah'        => $amount,
                'status_bayaran'=> 'upcoming', // Status sesuai contohmu
                'tarikh_terbit' => $today,
                'tarikh_tamat'  => $today->copy()->addDays(7), // Opsional: jatuh tempo
            ]);
        }
    }

    // Karena status student mungkin berubah di loop atas, kita refresh datanya agar yang 'pending' tidak muncul di list 'approved'
    $students = $students->where('is_approved', 'yes')->values();
    
    // dd([
    //     'Total Semua Data' => $students->count(),
    //     'Data Expired Saja' => $students->where('status', 'expired')->values()->toArray(),
    //     'Semua Status Yang Ada' => $students->pluck('status')->unique()->toArray()
    // ]);

    return view('admin.students.approved', compact('students', 'search'));
}

    public function show($id)
    {
        $student = Student::with('user')->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }

    public function enroll($id)
    {
        // 1. Ambil semua invoice student ini
        $invoices = StudentInvoice::where('student_id', $id)->get();

        $enrollments = [];

        foreach ($invoices as $invoice) {
            $courseId = $invoice->course_id;

            // 2. Cari semua class_schedule_student sesuai student + course
            $classScheduleStudents = ClassScheduleStudent::where('student_id', $id)
                ->whereHas('classSchedule', function ($query) use ($courseId) {
                    $query->where('course_id', $courseId);
                })
                ->get();

            foreach ($classScheduleStudents as $classScheduleStudent) {
                // 3. Ambil class schedule
                $classSchedule = ClassSchedule::find($classScheduleStudent->class_schedule_id);

                if ($classSchedule) {
                    $enrollments[] = [
                        'course'  => $invoice->course->nama_kos ?? 'Unknown',
                        'teacher' => $classSchedule->teacher->user->name ?? 'Unknown',
                        'jenis'   => $invoice->course->jenis ?? 'Unknown',
                    ];
                }
            }
        }

        $student = Student::with('user')->findOrFail($id);
        // dd($student);
        // dd($enrollments);

        return view('admin.students.enroll.index', compact('enrollments', 'student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $student->user_id,
            'ic_number' => 'required|string|max:50|unique:students,ic_number,' . $student->id,
            'phone'     => 'required|string|max:20',
            'address'   => 'nullable|string',
            'notes'     => 'nullable|string',
            'sponsor_name'  => 'nullable|string|max:255',
            'sponsor_phone' => 'nullable|string|max:20',
            'sponsor_email' => 'nullable|email',
        ]);

        // Update User
        $user = $student->user;
        $user->name  = $request->name;
        $user->email = $request->email;

        // kalau IC number diubah → update password
        if ($student->ic_number !== $request->ic_number) {
            $user->password = bcrypt($request->ic_number);
        }
        $user->save();

        // Update Student
        $student->update($request->only([
            'company_id',
            'ic_number',
            'phone',
            'address',
            'notes',
            'sponsor_name',
            'sponsor_phone',
            'sponsor_email',
        ]));

        return redirect()->route('admin.students.approved')->with('success', 'Student updated successfully');
    }

    // public function destroy($id)
    // {
    //     $student = Student::findOrFail($id);
    //     $student->delete();

    //     return back()->with('success', 'Student deleted successfully.');
    // }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $student = Student::findOrFail($id);

            // simpan user_id untuk hapus attendance
            $userId = $student->user_id;

            // Hapus attendance berdasarkan user_id
            StudentAttendance::where('user_id', $userId)->delete();

            // Hapus class schedule student (pivot table)
            ClassScheduleStudent::where('student_id', $id)->delete();

            // Cari semua invoice student ini
            $invoices = StudentInvoice::where('student_id', $id)->get();

            foreach ($invoices as $invoice) {
                // Hapus payment yang terkait invoice
                StudentPayment::where('student_invoice_id', $invoice->id)->delete();

                // Hapus review yang terkait invoice (kalau ada)
                Review::where('student_invoice_id', $invoice->id)->delete();
            }

            // Hapus semua invoice student ini
            StudentInvoice::where('student_id', $id)->delete();

            // Hapus review yang langsung terkait student
            Review::where('student_id', $id)->delete();

            // Terakhir hapus student
            $student->delete();

            // Hapus user yang terkait student
            User::where('id', $userId)->delete();
        });

        return back()->with('success', 'Student and related data deleted successfully.');
    }

    public function showApprovalForm($id)
    {
        $invoice = StudentInvoice::with('payment')
                    ->where('id', $id)
                    ->latest()
                    ->first();

        if (!$invoice) {
            $invoice = StudentInvoice::with('payment')
                        ->where('student_id', $id)
                        ->latest()
                        ->first();
        }

        if (!$invoice) {
            return redirect()->back()->with('error', 'Maklumat invoice atau pelajar tidak dijumpai.');
        }

        $student = Student::with('user')->find($invoice->student_id);
        if (!$student) {
            $student = Student::with('user')->findOrFail($id);
        }

        $companyId = Auth::user()->company->id;

        // // Ambil course terkait
        $course = Course::find($invoice->course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course tidak ditemukan.');
        }

        $maxStudent = $course->max_student;
        // dd($course);
        $semuaJadwal = collect();
        if ($course->jenis == 'kumpulan') {
            // Ambil semua class schedule untuk course tersebut, milik company, dan masih ada slot
            // $semuaJadwal = ClassSchedule::select('class_schedules.*')
            //     ->leftJoin('class_schedule_student', 'class_schedules.id', '=', 'class_schedule_student.class_schedule_id')
            //     ->selectRaw('COUNT(class_schedule_student.id) as total_students')
            //     ->where('class_schedules.course_id', $course->id)
            //     ->groupBy('class_schedules.id') // Primary key biasanya cukup di versi MySQL terbaru
            //     ->havingRaw('total_students < ?', [$course->max_student])
            //     ->get();
            $semuaJadwal = ClassSchedule::where('course_id', $course->id)
                ->whereIn('id', function($query) use ($course) {
                    $query->select('class_schedule_id')
                        ->from('class_schedule_student')
                        ->groupBy('class_schedule_id')
                        ->havingRaw('COUNT(*) < ?', [$course->max_student]);
                })
                ->orWhereDoesntHave('students') // Penting: Supaya jadwal yang masih 0 murid juga ikut muncul
                ->where('course_id', $course->id)
                ->get();
        }

        $now = Carbon::now();

        // mapping: key = bahasa indonesia (dipakai di blade), value = nama hari di DB (English)
        $dayMap = [
            'senin'  => 'Monday',
            'selasa' => 'Tuesday',
            'rabu'   => 'Wednesday',
            'kamis'  => 'Thursday',
            'jumat'  => 'Friday',
            'sabtu'  => 'Saturday',
            'minggu' => 'Sunday',
        ];

        $teachers = Teacher::where('company_id', $companyId)
            ->where('status', 'active')
            ->with([
                // relasi: classSchedules -> classScheduleStudent -> student -> invoices
                'classSchedules.classScheduleStudent.student.invoices' => function ($q) {
                    $q->orderByDesc('tarikh_terbit');
                },
                // relasi classSchedules -> classScheduleDays
                'classSchedules.classScheduleDays',
                // relasi unavailabilities
                'unavailabilities'
            ])
            ->get()
            ->map(function ($teacher) use ($now, $dayMap) {

                $activeStudents = collect();

                foreach ($teacher->classSchedules as $schedule) {
                    foreach ($schedule->classScheduleStudent as $css) {
                        $student = $css->student;
                        if (!$student) continue;

                        $lastInvoice = $student->invoices
                            ->where('course_id', $schedule->course_id)
                            ->sortByDesc('tarikh_terbit')
                            ->first();

                        if ($lastInvoice) {
                            $endDate = Carbon::parse($lastInvoice->tarikh_tamat)->endOfDay();
                            if ($now->lte($endDate)) {
                                $activeStudents->push($student);
                            }
                        }
                    }
                }

                $teacher->active_students_count = $activeStudents->unique('id')->count();
                $teacher->active_students = $activeStudents->unique('id');

                // === GABUNG TIMETABLE ===
                $timetable = collect();

                // 1. Tambah unavailabilities
                foreach ($teacher->unavailabilities ?? [] as $u) {
                    $dayKey = array_search($u->day, $dayMap);
                    // dd($dayKey);
                    $timetable->push([
                        'type'      => 'unavailable',
                        'day'       => $u->day,
                        'day_key'   => $dayKey ?: strtolower($u->day),
                        'start'     => $u->start_time,
                        'end'       => $u->end_time,
                    ]);
                }

                // 2. Tambah class schedule days (jadwal kelas yg sudah di-assign)
                foreach ($teacher->classSchedules as $schedule) {
                    foreach ($schedule->classScheduleDays as $day) {
                        $dayKey = array_search($day->hari, $dayMap);
                        // dd($dayKey);
                        $start_time = strtotime($day->jam_mulai);
                        $end_time = date("H:i", $start_time + ($day->durasi_menit * 60));
                        $timetable->push([
                            'type'       => 'class',
                            'class_id'   => $schedule->id,
                            'course_id'  => $schedule->course->nama_kos,
                            'day'        => $day->hari,
                            'day_key'    => $dayKey ?: strtolower($day->hari),
                            'start'      => $day->jam_mulai,
                            'end'        => $end_time,
                        ]);
                    }
                }

                // Pastikan collection
                $teacher->timetable = $timetable;

                return $teacher;
            })
            ->sortBy('active_students_count')
            ->values();

        // dd($invoice->payment->bukti_bayar);

        return view('admin.students.approval', compact('semuaJadwal', 'student', 'invoice', 'teachers', 'dayMap'));
    }


    public function approve(Request $request, $studentId, $invoiceId)
    {
        DB::transaction(function () use ($request, $studentId, $invoiceId) {
            $paidInvoice = StudentInvoice::where('id', $invoiceId)
                ->latest()
                ->first();

            $student = Student::with('user')->findOrFail($studentId);

            $course = Course::find($paidInvoice->course_id);
            if (!$course) {
                throw new \Exception('Course tidak ditemukan.');
            }

            $companyId = Auth::user()->company->id;
            $today = Carbon::now();

            // Update invoice
            $paidInvoice->status_bayaran = 'paid';
            $paidInvoice->tarikh_terbit = $today;
            $paidInvoice->tarikh_tamat = $today->copy()->addDays(30);
            $paidInvoice->save();

            // Jika admin pilih kelas yang sudah ada
            if ($request->filled('class_id')) {
                $class = ClassSchedule::findOrFail($request->class_id);
            } else {
                // Validasi input kelas baru
                $request->validate([
                    'nama_kelas' => 'required|string|max:255',
                    'teacher_id' => 'required|exists:teachers,id',
                    'allowance_type' => 'required|in:per_month,per_session,per_hour',
                    'allowance' => 'required|numeric|min:0',
                    'link_group' => 'nullable|url',
                    'notes' => 'nullable|string',
                    'days' => 'required|array',
                    'days.*.hari' => 'required|string',
                    'days.*.jam_mulai' => 'required',
                    'days.*.durasi_menit' => 'required|integer',
                ]);

                // Buat class baru
                $class = ClassSchedule::create([
                    'company_id' => $companyId,
                    'course_id' => $course->id,
                    'teacher_id' => $request->teacher_id,
                    'nama_kelas' => $request->nama_kelas,
                    'link_group' => $request->link_group,
                    'notes' => $request->notes,
                    'allowance_type' => $request->allowance_type,
                    'allowance' => $request->allowance,
                    'status' => 'akan_datang',
                ]);

                // Simpan jadwal
                foreach ($request->days as $jadwal) {
                    ClassScheduleDay::create([
                        'class_schedule_id' => $class->id,
                        'hari' => $jadwal['hari'],
                        'jam_mulai' => $jadwal['jam_mulai'],
                        'durasi_menit' => $jadwal['durasi_menit'],
                    ]);
                }
            }

            // 
            // student
            $student->is_approved = 'yes';
            $student->status = 'active';
            $student->sub_start = $today->toDateString();
            $student->sub_end = $today->copy()->addDays(30)->toDateString();
            $student->save();

            // Masukkan student ke kelas
            ClassScheduleStudent::create([
                'student_id' => $student->id,
                'class_schedule_id' => $class->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.students.pending')
            ->with('success', 'Pelajar telah diluluskan dan dimasukkan ke kelas.');
    }


    // Form single create
    public function create()
    {
        $companyId = Auth::user()->company->id;

        $courses = Course::where('company_id', $companyId)->get();
        return view('admin.students.create', compact('courses'));
    }

    // Store single student
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'ic_number' => 'required|string|unique:students,ic_number',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'sponsor_name' => 'nullable|string',
            'sponsor_phone' => 'nullable|string',
            'sponsor_email' => 'nullable|email',
        ]);

        $companyId = Auth::user()->company->id;

        // 1. Buat user student
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->ic_number),
            'role' => 'student',
        ]);

        // 2. Buat student
        $student = Student::create([
            'user_id' => $user->id,
            'company_id' => $companyId,
            'ic_number' => $request->ic_number,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'sponsor_name' => $request->sponsor_name,
            'sponsor_phone' => $request->sponsor_phone,
            'sponsor_email' => $request->sponsor_email,
        ]);

        $today = Carbon::now();
        $todayStr = $today->format('Ymd');

        $countToday = StudentInvoice::whereDate('created_at', $today->toDateString())->count() + 1;
        $sequence   = str_pad($countToday, 4, '0', STR_PAD_LEFT);
        $invoiceId  = "EMT-{$todayStr}-{$companyId}{$sequence}";

        $course = Course::get()->findOrFail($request->course_id);

        // 3. Buat invoice pertama (tarikh terbit & tamat nanti saat approve)
        StudentInvoice::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'nomor_invoice' => $invoiceId,
            'jumlah' => $course->fee, // bisa ditambah di form
            'status_bayaran' => 'upcoming',
        ]);


        return redirect()->route('admin.students.create')->with('success', 'Student berhasil dibuat!');
    }

    // Import dari Excel
    public function import(Request $request)
    {
        // dd("hi");
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $today = Carbon::now();
        $todayStr = $today->format('Ymd');


        $rows = Excel::toCollection(new class implements WithHeadingRow {
            public function model(array $row) {}
        }, $request->file('file'))->first();

        $companyId = Auth::user()->company->id;

        $count = 0;
        
        $course = Course::get()->findOrFail($request->course_id);
        // dd($course);

        foreach ($rows as $row) {
            // Skip kalau email/ic_number kosong
            if (empty($row['email']) || empty($row['ic_number'])) continue;

            // cek apakah email sudah ada
            if (User::where('email', $row['email'])->exists()) continue;

            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make($row['ic_number']),
                'role' => 'student',
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'company_id' => $companyId,
                'ic_number' => $row['ic_number'],
                'phone' => $row['phone'] ?? null,
                'address' => $row['address'] ?? null,
                'notes' => $row['notes'] ?? null,
                'sponsor_name' => $row['sponsor_name'] ?? null,
                'sponsor_phone' => $row['sponsor_phone'] ?? null,
                'sponsor_email' => $row['sponsor_email'] ?? null,
            ]);

            $countToday = StudentInvoice::whereDate('created_at', $today->toDateString())->count() + 1;
            $sequence   = str_pad($countToday, 4, '0', STR_PAD_LEFT);
            $invoiceId  = "EMT-{$todayStr}-{$companyId}{$sequence}";

            // Buat invoice pertama
            StudentInvoice::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'nomor_invoice' => $invoiceId,
                'jumlah' => $course->fee,
                'status_bayaran' => 'upcoming',
            ]);

            $count++;
        }

        return redirect()->back()->with('success', "$count Students berhasil diimport!");
    }

    

}
