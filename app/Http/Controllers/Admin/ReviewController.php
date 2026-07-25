<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyReview;
use App\Models\Company;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Cek apakah popup review perlu ditampilkan di dashboard
     */
    public function shouldShowReviewPopup()
    {
        // $company = Auth::user(); // asumsi company login
        $user = Auth::user();

        $company = $this->resolveCompany($user);

        if (!$company) {
            return false;
        }

        // Hitung umur company
        $companyAgeInDays = Carbon::parse($company->created_at)->diffInDays(now());

        // Cek apakah sudah pernah review
        $alreadyReviewed = CompanyReview::where('company_id', $company->id)->exists();

        // Muncul jika:
        // - umur >= 7 hari
        // - belum pernah review
        // dd( $companyAgeInDays, $alreadyReviewed );
        return $companyAgeInDays >= 7 && !$alreadyReviewed;
    }

    /**
     * Halaman list review (opsional)
     */
    public function index()
    {
        $user = Auth::user();

        $company = $this->resolveCompany($user);

        if (!$company) {
            return redirect()->route('a.dashboard.index')
                ->with('error', 'Akun admin ini belum terhubung dengan syarikat.');
        }

        $reviews = CompanyReview::where('company_id', $company->id)
            ->latest()
            ->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Form create review
     */
    public function create()
    {
        return view('admin.reviews.create');
    }

    /**
     * Simpan review baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $user = Auth::user();

        $company = $this->resolveCompany($user);

        if (!$company) {
            return redirect()->route('a.dashboard.index')
                ->with('error', 'Akun admin ini belum terhubung dengan syarikat.');
        }

        CompanyReview::create([
            'company_id' => $company->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return redirect()->route('company.reviews.index')
            ->with('success', 'Terima kasih atas feedback Anda!');
    }

    /**
     * Form edit review
     */
    public function edit($id)
    {
        $user = Auth::user();

        $company = $this->resolveCompany($user);

        if (!$company) {
            return redirect()->route('a.dashboard.index')
                ->with('error', 'Akun admin ini belum terhubung dengan syarikat.');
        }

        $review = CompanyReview::where('company_id', $company->id)
            ->findOrFail($id);

        return view('admin.reviews.edit', compact('review'));
    }

    /**
     * Update review
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $user = Auth::user();

        $company = $this->resolveCompany($user);

        if (!$company) {
            return redirect()->route('a.dashboard.index')
                ->with('error', 'Akun admin ini belum terhubung dengan syarikat.');
        }

        $review = CompanyReview::where('company_id', $company->id)
            ->findOrFail($id);

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('company.reviews.index')
            ->with('success', 'Review berhasil diperbarui');
    }

    /**
     * Hapus review
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $company = $this->resolveCompany($user);

        if (!$company) {
            return redirect()->route('a.dashboard.index')
                ->with('error', 'Akun admin ini belum terhubung dengan syarikat.');
        }

        $review = CompanyReview::where('company_id', $company->id)
            ->findOrFail($id);

        $review->delete();

        return redirect()->back()
            ->with('success', 'Review berhasil dihapus');
    }

    private function resolveCompany($user): ?Company
    {
        return Company::where('user_id', $user->id)->first()
            ?? Staff::with('company')->where('user_id', $user->id)->first()?->company;
    }
}
