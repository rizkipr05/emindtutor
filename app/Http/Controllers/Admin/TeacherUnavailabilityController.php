<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherUnavailability;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TeacherUnavailabilityController extends Controller
{
    // tampilkan semua guru beserta unavailability mereka
    public function index()
    {
        $teachers = Teacher::with('unavailabilities')->get();
        return view('admin.unavailabilities.index', compact('teachers'));
    }

    public function show($teacherId)
    {
        $teacher = Teacher::with([
            'classSchedules.course',
            'classSchedules.classScheduleStudent.student.user',
        ])->findOrFail($teacherId);

        $unavail = TeacherUnavailability::where('teacher_id', $teacher->id)->get();

        // Gabungkan class schedule dan unavailability
        $unavailabilities = collect();

        // Tambahkan class schedules
        foreach ($teacher->classSchedules as $schedule) {
            foreach ($schedule->classScheduleDays as $class) {
                $start_time = strtotime($class->jam_mulai);
                $end_time = date("H:i", $start_time + ($class->durasi_menit * 60));
                $unavailabilities->push([
                    'type' => 'class',
                    'day' => $class->hari ?? null,
                    'start' => $class->jam_mulai ?? null,
                    'end' => $end_time ?? null,
                    'course' => $schedule->course->nama_kos ?? null,
                ]);
            }
        }

        // Tambahkan unavailabilities
        foreach ($unavail as $ua) {
            $unavailabilities->push([
                'type' => 'unavailable',
                'day' => $ua->day ?? null,
                'start' => $ua->start_time ?? null,
                'end' => $ua->end_time ?? null,
                'reason' => $ua->reason ?? null,
            ]);
        }

        // dd($unavailabilities);
        return view('admin.teachers.show.availability', compact('teacher', 'unavailabilities'));
    }

    public function edit($teacherId)
    {
        $teacher = Teacher::with('unavailabilities')->findOrFail($teacherId);
        $unavailabilities = $teacher->unavailabilities;

        return view('admin.teachers.show.availability.edit', compact('teacher', 'unavailabilities'));
    }

    public function update(Request $request, $teacherId)
    {
        $teacher = Teacher::findOrFail($teacherId);

        // Validasi dasar
        $rules = [
            'unavailability' => 'array',
        ];
        // Optional: validasi detail (looping) kalau mau strict
        $request->validate($rules);

        // Hapus semua unavailability lama
        $teacher->unavailabilities()->delete();

        if ($request->filled('unavailability')) {
            $allowedDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

            foreach ($request->unavailability as $dayName => $slots) {
                // Terima baik kunci numeric (0..6) atau string nama hari
                if (is_numeric($dayName)) {
                    $dayName = $allowedDays[(int)$dayName] ?? null;
                }
                if (!in_array($dayName, $allowedDays, true)) {
                    continue;
                }

                foreach ((array)$slots as $slot) {
                    $start = Arr::get($slot, 'start_time');
                    $end   = Arr::get($slot, 'end_time');

                    if (!$start || !$end) {
                        continue;
                    }

                    // Normalisasi ke HH:MM:SS (input <input type="time"> biasanya HH:MM)
                    if (strlen($start) === 5) { $start .= ':00'; }
                    if (strlen($end)   === 5) { $end   .= ':00'; }

                    // Pastikan end > start (perbandingan string aman untuk HH:MM:SS)
                    if ($end <= $start) {
                        continue;
                    }

                    $teacher->unavailabilities()->create([
                        'day'        => $dayName,
                        'start_time' => $start, // TIME di DB
                        'end_time'   => $end,   // TIME di DB
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.teachers.show', $teacherId)
            ->with('success', 'Availability updated successfully.');
    }

    // form tambah unavailable
    public function create(Teacher $teacher)
    {
        return view('admin.unavailabilities.create', compact('teacher'));
    }

    // simpan unavailable
    public function store(Request $request, Teacher $teacher)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'reason'     => 'nullable|string|max:255',
        ]);

        TeacherUnavailability::create([
            'teacher_id' => $teacher->id,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'reason'     => $request->reason,
        ]);

        return redirect()->route('admin.unavailabilities.index')->with('success', 'Data unavailability berhasil ditambahkan.');
    }

    // hapus unavailable
    public function destroy(TeacherUnavailability $unavailability)
    {
        $unavailability->delete();
        return redirect()->back()->with('success', 'Data unavailability berhasil dihapus.');
    }
}