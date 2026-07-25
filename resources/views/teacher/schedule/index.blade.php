@extends('layouts.teacher')

@section('title', __('teacher/schedule.page_title', ['site' => 'EMindTutor']))

@section('content')
<style>
    .teacher-schedule-page {
        max-width: 1280px;
        margin: 0 auto;
        color: #0f172a;
    }

    .schedule-page-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .schedule-icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin-top: 2px;
        border-radius: 10px;
        color: #a000ff;
        background: #f2d9ff;
    }

    .schedule-page-title {
        margin: 0;
        font-size: 30px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: 0;
    }

    .schedule-page-subtitle {
        margin-top: 6px;
        color: #667085;
        font-size: 14px;
    }

    .schedule-alert {
        margin-bottom: 18px;
        padding: 10px 14px;
        border: 1px solid #6b7280;
        border-radius: 12px;
        background: #fbffe9;
        color: #243044;
        font-size: 13px;
        font-weight: 500;
    }

    .schedule-message {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 14px;
    }

    .schedule-message-success {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #15803d;
    }

    .schedule-message-error {
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
    }

    .schedule-empty {
        padding: 40px;
        text-align: center;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        background: #fff;
    }

    .schedule-table-wrap {
        overflow-x: auto;
        border: 1px solid #e7ebf3;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.05);
    }

    .schedule-grid {
        min-width: 1180px;
    }

    .schedule-head,
    .schedule-row {
        display: grid;
        grid-template-columns: 104px repeat(7, minmax(140px, 1fr));
    }

    .schedule-head {
        min-height: 64px;
        border-bottom: 1px solid #e9edf5;
        background: #f7f8fb;
    }

    .schedule-row {
        min-height: 205px;
        border-bottom: 1px solid #edf0f5;
    }

    .schedule-row:last-child {
        border-bottom: 0;
    }

    .schedule-head-cell,
    .schedule-time-head {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px 16px;
        color: #66708d;
        font-size: 12px;
        font-weight: 800;
        text-align: center;
    }

    .schedule-time-head {
        justify-content: flex-start;
        gap: 8px;
        background: #f1f3f8;
        text-transform: uppercase;
    }

    .schedule-head-cell {
        flex-direction: column;
        border-left: 1px solid #e9edf5;
    }

    .schedule-day-abbr {
        font-size: 12px;
        line-height: 1;
        text-transform: uppercase;
    }

    .schedule-day-name {
        margin-top: 8px;
        font-size: 12px;
        line-height: 1;
        font-weight: 700;
    }

    .schedule-time-slot {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 22px 14px;
        color: #1f2a44;
        font-size: 14px;
        font-weight: 800;
        background: #fbfcff;
    }

    .schedule-cell {
        min-height: 205px;
        padding: 8px;
        border-left: 1px solid #edf0f5;
        background: #fff;
    }

    .schedule-card-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .schedule-card {
        min-height: 152px;
        padding: 10px;
        border-left: 4px solid #6c63ff;
        border-radius: 10px;
        background: #e9edff;
        box-shadow: 0 2px 7px rgba(79, 70, 229, 0.12);
    }

    .schedule-card-top {
        display: flex;
        align-items: stretch;
        gap: 8px;
    }

    .schedule-card-main {
        min-width: 0;
        flex: 1;
    }

    .schedule-class-name {
        margin: 0;
        color: #0f172a;
        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .schedule-course-name {
        margin-top: 6px;
        color: #66708d;
        font-size: 11px;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .schedule-time-range {
        margin-top: 8px;
        color: #0f172a;
        font-size: 11px;
        line-height: 1.2;
        font-weight: 800;
    }

    .schedule-edit-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        min-height: 76px;
        padding: 8px;
        border-radius: 7px;
        background: rgba(255, 255, 255, 0.72);
        color: #66708d;
        font-size: 13px;
        line-height: 1.35;
        font-weight: 800;
        text-align: center;
        text-decoration: none;
        transition: background .15s ease, color .15s ease;
    }

    .schedule-edit-link:hover {
        background: #fff;
        color: #4f46e5;
    }

    .schedule-card-footer {
        margin-top: 10px;
        padding-top: 8px;
        border-top: 1px solid rgba(255, 255, 255, 0.8);
    }

    .schedule-student-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #66708d;
        font-size: 11px;
    }

    .schedule-student-list {
        margin-top: 8px;
        padding: 5px 8px;
        overflow: hidden;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.62);
        color: #66708d;
        font-size: 11px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .schedule-icon-morning {
        color: #f59e0b;
    }

    .schedule-icon-afternoon {
        color: #fb7185;
    }

    .schedule-icon-night {
        color: #a000ff;
    }

    @media (max-width: 768px) {
        .schedule-page-title {
            font-size: 24px;
        }

        .schedule-grid {
            min-width: 1080px;
        }
    }
</style>

<div class="teacher-schedule-page">
    <div class="schedule-page-header">
        <div class="schedule-icon-box">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
            </svg>
        </div>
        <div>
            <h1 class="schedule-page-title">Weekly Class Schedule</h1>
            <p class="schedule-page-subtitle">View your teaching schedule for this week</p>
        </div>
    </div>

    @if(session('success'))
        <div class="schedule-message schedule-message-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="schedule-message schedule-message-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="schedule-alert">
        Schedule editing is available anytime.
    </div>

    @if(empty($scheduleSummaries))
        <div class="schedule-empty">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('teacher/schedule.empty_title') }}</h3>
            <p class="mt-2 text-sm text-slate-500">{{ __('teacher/schedule.empty_desc') }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ __('teacher/schedule.empty_contact') }}</p>
        </div>
    @else
        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $timeSlots = ['Morning', 'Afternoon', 'Night'];
            $slotIconClasses = [
                'Morning' => 'schedule-icon-morning',
                'Afternoon' => 'schedule-icon-afternoon',
                'Night' => 'schedule-icon-night',
            ];
        @endphp

        <div class="schedule-table-wrap">
            <div class="schedule-grid">
                <div class="schedule-head">
                    <div class="schedule-time-head">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('teacher/schedule.table_time') }}
                    </div>
                    @foreach($days as $day)
                        <div class="schedule-head-cell">
                            <div class="schedule-day-abbr">{{ __('teacher/schedule.days_abbr')[$day] }}</div>
                            <div class="schedule-day-name">{{ __('teacher/schedule.days_full')[$day] }}</div>
                        </div>
                    @endforeach
                </div>

                @foreach($timeSlots as $timeSlot)
                    <div class="schedule-row">
                        <div class="schedule-time-slot">
                            @if($timeSlot === 'Night')
                                <svg class="{{ $slotIconClasses[$timeSlot] }}" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.8A8.5 8.5 0 1111.2 3a6.5 6.5 0 009.8 9.8z" />
                                </svg>
                            @else
                                <svg class="{{ $slotIconClasses[$timeSlot] }}" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V2m0 20v-2m8-8h2M2 12h2m13.66-5.66l1.41-1.41M4.93 19.07l1.41-1.41m0-11.32L4.93 4.93m14.14 14.14l-1.41-1.41M12 8a4 4 0 100 8 4 4 0 000-8z" />
                                </svg>
                            @endif
                            {{ __('teacher/schedule.time_slots')[$timeSlot] }}
                        </div>

                        @foreach($days as $day)
                            <div class="schedule-cell">
                                <div class="schedule-card-list">
                                    @foreach(($weeklySchedule[$timeSlot][$day] ?? []) as $schedule)
                                        <div class="schedule-card">
                                            <div class="schedule-card-top">
                                                <div class="schedule-card-main">
                                                    <h3 class="schedule-class-name">{{ $schedule['class_name'] }}</h3>
                                                    <p class="schedule-course-name">{{ $schedule['course_name'] }}</p>
                                                    <p class="schedule-time-range">{{ $schedule['time_range'] }}</p>
                                                </div>
                                                <a href="{{ route('teacher.schedule.edit', $schedule['id']) }}" class="schedule-edit-link">
                                                    Edit<br>anytime
                                                </a>
                                            </div>

                                            <div class="schedule-card-footer">
                                                <div class="schedule-student-count">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19a6 6 0 00-12 0m12 0a6 6 0 00-12 0m12 0h6a6 6 0 00-9-5.2M9 11a4 4 0 100-8 4 4 0 000 8zm8-1a3 3 0 100-6" />
                                                    </svg>
                                                    {{ $schedule['student_count'] }} {{ __('teacher/schedule.students') }}
                                                </div>
                                                @if(!empty($schedule['students']))
                                                    <div class="schedule-student-list">
                                                        {{ implode(', ', array_slice($schedule['students'], 0, 2)) }}
                                                        @if(count($schedule['students']) > 2)
                                                            +{{ count($schedule['students']) - 2 }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
