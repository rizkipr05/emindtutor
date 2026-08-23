<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Models\Teacher;
use App\Models\WhatsAppReminderTemplate;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class WhatsAppReminderTemplateController extends Controller
{
    public function index(Request $request)
    {
        $audience = $request->query('audience', 'teachers');
        abort_unless(in_array($audience, ['teachers', 'students', 'company'], true), 404);

        return view('super-admin.whatsapp_reminder_templates.index', [
            'audience' => $audience,
            'templates' => WhatsAppReminderTemplate::where('audience', $audience)->latest()->get(),
            'companies' => Company::orderBy('nama_wakil_syarikat')->get(['id', 'nama_wakil_syarikat', 'no_telefon']),
            'hasFonnteToken' => filled(SystemSetting::fonnteToken()),
        ]);
    }

    public function updateFonnteToken(Request $request)
    {
        $validated = $request->validate([
            'fonnte_token' => ['required', 'string', 'max:255'],
        ]);

        SystemSetting::updateOrCreate(
            ['setting_key' => 'fonnte_token'],
            [
                'setting_group' => 'communications',
                'setting_label' => 'Fonnte Token (WhatsApp Reminder)',
                'setting_type' => 'password',
                'setting_value' => Crypt::encryptString($validated['fonnte_token']),
            ]
        );

        return redirect()
            ->route('superadmin.whatsapp-reminder-templates.index', ['audience' => $request->query('audience', 'teachers')])
            ->with('success', 'Token Fonnte berjaya disimpan.');
    }

    public function create(Request $request)
    {
        return view('super-admin.whatsapp_reminder_templates.form', [
            'template' => new WhatsAppReminderTemplate(['audience' => $request->query('audience', 'teachers')]),
        ]);
    }

    public function store(Request $request)
    {
        WhatsAppReminderTemplate::create($this->validated($request));
        return redirect()->route('superadmin.whatsapp-reminder-templates.index', ['audience' => $request->audience])->with('success', __('super_admin/reminder_templates.created'));
    }

    public function edit(WhatsAppReminderTemplate $template)
    {
        return view('super-admin.whatsapp_reminder_templates.form', compact('template'));
    }

    public function update(Request $request, WhatsAppReminderTemplate $template)
    {
        $template->update($this->validated($request, $template));
        return redirect()->route('superadmin.whatsapp-reminder-templates.index', ['audience' => $template->audience])->with('success', __('super_admin/reminder_templates.updated'));
    }

    public function destroy(WhatsAppReminderTemplate $template)
    {
        $audience = $template->audience;
        $template->delete();
        return redirect()->route('superadmin.whatsapp-reminder-templates.index', ['audience' => $audience])->with('success', __('super_admin/reminder_templates.deleted'));
    }

    public function send(Request $request, WhatsAppReminderTemplate $template)
    {
        $request->validate(['company_id' => 'required|exists:companies,id']);
        abort_unless($template->is_active, 422, __('super_admin/reminder_templates.not_active'));

        $company = Company::findOrFail($request->integer('company_id'));
        if ($template->audience === 'company' && ! filled($company->no_telefon)) {
            return back()->with('error', __('super_admin/reminder_templates.company_phone_missing'));
        }
        $recipients = match ($template->audience) {
            'teachers' => Teacher::where('company_id', $company->id)->where('status', 'active')->get(),
            'students' => Student::where('company_id', $company->id)->where('status', 'active')->get(),
            'company' => collect([$company]),
        };
        $sentCount = 0;
        $notificationType = 'manual_template_' . $template->id . '_' . now()->format('YmdHisv');
        $whatsApp = app(WhatsAppNotificationService::class);

        foreach ($recipients as $recipient) {
            $phone = $template->audience === 'company' ? $recipient->no_telefon : $recipient->phone;
            if ($whatsApp->sendOnce($recipient, $notificationType, $phone, $template->message)) {
                $sentCount++;
            }
        }

        if ($sentCount === 0) {
            return back()->with('error', __('super_admin/reminder_templates.send_failed'));
        }

        return back()->with('success', __('super_admin/reminder_templates.send_queued', ['count' => $sentCount]));
    }

    private function validated(Request $request, ?WhatsAppReminderTemplate $template = null): array
    {
        $validated = $request->validate([
            'audience' => 'required|in:teachers,students,company',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'is_active' => 'nullable|boolean',
        ]);

        return $validated + ['is_active' => $request->boolean('is_active')];
    }
}
