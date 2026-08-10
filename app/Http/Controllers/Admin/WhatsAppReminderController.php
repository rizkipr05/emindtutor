<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\WhatsAppReminder;
use App\Models\WhatsAppReminderTemplate;
use Illuminate\Http\Request;

class WhatsAppReminderController extends Controller
{
    public function index()
    {
        $reminders = WhatsAppReminder::with('company')->latest()->get();
        return view('admin.whatsapp_reminders.index', compact('reminders'));
    }

    public function create()
    {
        return view('admin.whatsapp_reminders.form', ['reminder' => new WhatsAppReminder(), 'companies' => Company::orderBy('nama_wakil_syarikat')->get(), 'templates' => $this->templates()]);
    }

    public function store(Request $request)
    {
        WhatsAppReminder::create($this->validated($request));
        return redirect()->route('superadmin.whatsapp-reminders.index')->with('success', 'Reminder berhasil dibuat.');
    }

    public function edit(WhatsAppReminder $reminder)
    {
        return view('admin.whatsapp_reminders.form', ['reminder' => $reminder, 'companies' => Company::orderBy('nama_wakil_syarikat')->get(), 'templates' => $this->templates()]);
    }

    public function update(Request $request, WhatsAppReminder $reminder)
    {
        $reminder->update($this->validated($request));
        return redirect()->route('superadmin.whatsapp-reminders.index')->with('success', 'Reminder berhasil diperbarui.');
    }

    public function destroy(WhatsAppReminder $reminder)
    {
        $reminder->delete();
        return back()->with('success', 'Reminder berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'company_id' => 'required|exists:companies,id',
            'audience' => 'required|in:teachers,students,company',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'frequency' => 'required|in:once,daily,weekly,monthly',
            'next_run_at' => 'required|date',
            'is_active' => 'nullable|boolean',
        ]) + ['is_active' => $request->boolean('is_active')];
    }

    private function templates()
    {
        return WhatsAppReminderTemplate::where('is_active', true)
            ->orderBy('audience')
            ->orderBy('title')
            ->get(['id', 'audience', 'title', 'message']);
    }

}