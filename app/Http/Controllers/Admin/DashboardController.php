<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\Course;
use App\Models\Announcement;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $company = $this->resolveCompany($user);

        if (!$company) {
            return redirect()->route('login')
                ->with('error', 'Akun admin ini belum terhubung dengan syarikat.');
        }

        $today = now()->toDateString();

        // Ambil langganan aktif, sudah dibayar, dan dalam rentang tanggal
        $activeSubscription = CompanyInvoice::where('company_id', $company->id)
            ->where('status', 'active')
            ->where('status_bayaran', 'paid')
            ->whereDate('tarikh_terbit', '<=', $today)
            ->whereDate('tarikh_tamat', '>=', $today)
            ->latest()
            ->with('package')
            ->first();
        $remainingDays = 0;
        $expiryDate    = null;
        $totalDays     = 0;
        if ($activeSubscription) {
            $today         = Carbon::today();
            $startDate     = Carbon::parse($activeSubscription->tarikh_terbit);
            $endDate       = Carbon::parse($activeSubscription->tarikh_tamat);
            $remainingDays = max(0, $today->diffInDays($endDate) + 1);
            $expiryDate    = $endDate;
            $totalDays     = max(1, $startDate->diffInDays($endDate) + 1);
        }

        $summary = [
            'courses'       => Course::where('company_id', $company->id)->count(),
            'teachers'      => Teacher::where('company_id', $company->id)->count(),
            'students'      => Student::where('company_id', $company->id)->count(),
            'staff'         => Staff::where('company_id', $company->id)->count(),
            'announcements' => Announcement::where('created_by', $user->id)->count(),
        ];

        return view('admin.dashboard', [
            'company'         => $company,
            'user'            => $user,
            'subscription'    => $activeSubscription,
            'remainingDays'   => $remainingDays,
            'expiryDate'      => $expiryDate,
            'totalDays'       => $totalDays,
            'summary'         => $summary,
        ]);
    }

    public function edit()
    {
        $user = Auth::user()->load('company');
        return view('admin.edit', compact('user'));
    }

    // Tambah atau ubah link perusahaan
    public function addLink(Request $request, $id)
    {
        $request->validate([
            'link' => 'nullable|url', // boleh kosong, harus URL valid jika diisi
        ]);

        $company = Company::findOrFail($id);
        $company->link = $request->link;
        $company->save();

        return redirect()->back()->with('success', 'Link perusahaan berhasil diperbarui.');
    }

    private function resolveCompany($user): ?Company
    {
        return Company::where('user_id', $user->id)->first()
            ?? Staff::with('company')->where('user_id', $user->id)->first()?->company;
    }
}
