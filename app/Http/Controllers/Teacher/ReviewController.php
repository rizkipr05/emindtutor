<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $teacherId = Auth::user()->teachers->id;
        // dd($teacherId);
        
        // Get reviews yang sudah dibuat oleh student ini
        $existingReviews = Review::where('teacher_id', $teacherId)
            ->with(['teacher', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('teacher.reviews.index', compact('existingReviews'));
    }
}
