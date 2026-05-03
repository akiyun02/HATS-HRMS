@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Performance</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Performance</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Conduct reviews and track historical organizational performance metrics.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <button onclick="document.getElementById('review-modal').classList.remove('hidden')" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Assessment
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-emerald-50 dark:bg-emerald-900/20 p-4 border border-emerald-200 dark:border-emerald-800">
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Review History -->
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Assessment Registry</h3>
        </div>
        <div class="min-w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-white dark:bg-slate-900">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-[10px] font-bold uppercase tracking-wider text-slate-500">Employee</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Review Date</th>
                        <th scope="col" class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500">Rating</th>
                        <th scope="col" class="px-3 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 pr-6">Reviewer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($reviews as $review)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <td class="whitespace-nowrap py-4 px-6">
                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $review->user->name }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">
                            {{ $review->review_date->format('M d, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-center">
                            <div class="flex items-center justify-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-3 w-3 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-800' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-bold text-slate-500 text-right pr-6">
                            {{ $review->reviewer->name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-3 border-t border-slate-100 dark:border-slate-800">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

<!-- Assessment Modal -->
<div id="review-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Conduct Assessment</h3>
            <button onclick="document.getElementById('review-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('performance.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="form-label">Employee</label>
                <select name="user_id" required class="form-input">
                    <option value="">Select staff member...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('user_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Assessment Date</label>
                    <input type="date" name="review_date" value="{{ date('Y-m-d') }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Rating</label>
                    <select name="rating" required class="form-input">
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Very Good</option>
                        <option value="3">3 - Good</option>
                        <option value="2">2 - Fair</option>
                        <option value="1">1 - Poor</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Evaluator Feedback</label>
                <textarea name="feedback" rows="4" required class="form-input" placeholder="Summarize strengths and areas for improvement..."></textarea>
            </div>
            <button type="submit" class="w-full bg-brand-600 text-white py-2.5 rounded-md font-bold text-sm shadow-sm hover:bg-brand-700 transition-colors">Publish Assessment</button>
        </form>
    </div>
</div>
@endsection
