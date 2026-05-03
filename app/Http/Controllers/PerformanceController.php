<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $reviews = $user->performanceReviews()->with('reviewer')->orderBy('review_date', 'desc')->get();

        return view('performance.index', compact('reviews'));
    }

    public function adminIndex()
    {
        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'Employee');
        })->get();

        $reviews = PerformanceReview::with(['user', 'reviewer'])->orderBy('review_date', 'desc')->paginate(20);

        return view('performance.admin-index', compact('employees', 'reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'review_date' => 'required|date',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string',
        ]);

        PerformanceReview::create([
            'user_id' => $validated['user_id'],
            'reviewer_id' => auth()->id(),
            'review_date' => $validated['review_date'],
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback'],
        ]);

        return back()->with('success', 'Performance review submitted successfully.');
    }
}
