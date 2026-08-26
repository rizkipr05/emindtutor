<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mirror existing clocking records into the legacy attendance table.
     * Admin reports and the student progress page consume student_attendances.
     */
    public function up(): void
    {
        DB::table('student_clocking_sessions')
            ->select('student_id', 'class_schedule_id', 'clock_date')
            ->distinct()
            ->orderBy('student_id')
            ->chunk(100, function ($clockingDays) {
                foreach ($clockingDays as $clockingDay) {
                    $student = DB::table('students')->select('user_id')->where('id', $clockingDay->student_id)->first();

                    if (! $student) {
                        continue;
                    }

                    $sessions = DB::table('student_clocking_sessions')
                        ->where('student_id', $clockingDay->student_id)
                        ->where('class_schedule_id', $clockingDay->class_schedule_id)
                        ->whereDate('clock_date', $clockingDay->clock_date)
                        ->orderBy('clock_in_at')
                        ->get();

                    if ($sessions->isEmpty()) {
                        continue;
                    }

                    $firstSession = $sessions->first();
                    $lastCompletedSession = $sessions->whereNotNull('clock_out_at')->last();
                    $scheduleDay = DB::table('class_schedule_days')
                        ->where('class_schedule_id', $clockingDay->class_schedule_id)
                        ->where('hari', Carbon::parse($clockingDay->clock_date)->format('l'))
                        ->first();
                    $classStart = $scheduleDay?->jam_mulai
                        ? Carbon::parse($clockingDay->clock_date . ' ' . $scheduleDay->jam_mulai, 'Asia/Kuala_Lumpur')
                        : null;
                    $clockIn = Carbon::parse($firstSession->clock_in_at, 'Asia/Kuala_Lumpur');
                    $status = $classStart && $clockIn->greaterThan($classStart) ? 'late' : 'present';

                    $attendance = DB::table('student_attendances')
                        ->where('user_id', $student->user_id)
                        ->where('class_schedule_id', $clockingDay->class_schedule_id)
                        ->whereDate('attendance_date', $clockingDay->clock_date)
                        ->first();

                    $data = [
                        'status' => $status,
                        'attendance_time' => $clockIn->format('H:i:s'),
                        'check_out_time' => $lastCompletedSession ? Carbon::parse($lastCompletedSession->clock_out_at, 'Asia/Kuala_Lumpur')->format('H:i:s') : null,
                        'attendance_photo' => $firstSession->clock_in_photo,
                        'updated_at' => now(),
                    ];

                    if ($attendance) {
                        DB::table('student_attendances')->where('id', $attendance->id)->update($data);
                    } else {
                        DB::table('student_attendances')->insert($data + [
                            'user_id' => $student->user_id,
                            'class_schedule_id' => $clockingDay->class_schedule_id,
                            'attendance_date' => $clockingDay->clock_date,
                            'note' => null,
                            'created_at' => now(),
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        // Historical attendance rows are intentionally retained on rollback.
    }
};
