@extends('layouts.app')

@section('content')
<div class="space-y-6 flex flex-col h-[calc(100vh-8rem)]">
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5 shrink-0">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Recruitment</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage organizational candidates and track hiring progression.</p>
        </div>
        <div class="mt-4 flex sm:ml-16 sm:mt-0 gap-3">
            <a href="{{ route('recruitment.export') }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Export Data
            </a>
            <a href="{{ route('recruitment.create') }}" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                New Posting
            </a>
        </div>
    </div>

    <!-- Active Postings Quick View -->
    <div class="flex gap-4 overflow-x-auto pb-2 shrink-0 no-scrollbar">
        @foreach($jobs as $job)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-md p-3 min-w-[250px] shadow-sm flex justify-between items-center shrink-0">
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $job->title }}</h4>
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">{{ $job->applicants_count }} Applicants</p>
            </div>
            <span class="h-2 w-2 rounded-full {{ $job->status === 'Active' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
        </div>
        @endforeach
    </div>

    <!-- Kanban Pipeline -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden pb-4">
        <div class="flex h-full gap-6 min-w-max items-start">
            @foreach(['Applied', 'Screening', 'Interview', 'Offer', 'Hired', 'Rejected'] as $stage)
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 w-80 flex flex-col h-full shrink-0">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-100/50 dark:bg-slate-800 rounded-t-lg shrink-0">
                    <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ $stage }}</h3>
                    <span class="bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded shadow-sm border border-slate-200 dark:border-slate-700">
                        {{ count($pipeline[$stage]) }}
                    </span>
                </div>
                
                <div class="p-3 flex-1 overflow-y-auto space-y-3">
                    @foreach($pipeline[$stage] as $candidate)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md p-4 shadow-sm hover:border-brand-500 transition-colors group relative cursor-pointer" onclick="openCandidateModal({{ $candidate }})">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $candidate->first_name }} {{ $candidate->last_name }}</h4>
                            @if($candidate->resume_path)
                            <a href="{{ Storage::url($candidate->resume_path) }}" target="_blank" onclick="event.stopPropagation()" class="text-slate-400 hover:text-brand-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            </a>
                            @endif
                        </div>
                        <p class="text-xs font-semibold text-brand-600 dark:text-brand-400 truncate">{{ $candidate->jobPosting->title }}</p>
                        <div class="mt-3 flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            <span>{{ $candidate->created_at->format('M d') }}</span>
                            
                            <!-- Quick Move Actions -->
                            <form action="{{ route('applicants.update-status', $candidate) }}" method="POST" onclick="event.stopPropagation()" class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf @method('PATCH')
                                @if($stage !== 'Rejected')
                                <button type="submit" name="status" value="Rejected" class="p-1 text-red-500 hover:bg-red-50 rounded" title="Reject">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                @endif
                                
                                @php
                                    $next = '';
                                    if($stage == 'Applied') $next = 'Screening';
                                    if($stage == 'Screening') $next = 'Interview';
                                    if($stage == 'Interview') $next = 'Offer';
                                    if($stage == 'Offer') $next = 'Hired';
                                @endphp
                                
                                @if($next)
                                <button type="submit" name="status" value="{{ $next }}" class="p-1 text-emerald-600 hover:bg-emerald-50 rounded" title="Move to {{ $next }}">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                                @endif
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @if(count($pipeline[$stage]) === 0)
                    <div class="p-4 text-center border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-md">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">No Candidates</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Candidate Details Modal -->
<div id="candidate-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Candidate Profile</h3>
            <button onclick="document.getElementById('candidate-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6 grid grid-cols-2 gap-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Name</p>
                <p id="c-name" class="text-lg font-bold text-slate-900 dark:text-white"></p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Position</p>
                <p id="c-job" class="text-lg font-bold text-brand-600 dark:text-brand-400"></p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Contact</p>
                <p id="c-email" class="text-sm font-semibold text-slate-700 dark:text-slate-300"></p>
                <p id="c-phone" class="text-sm font-semibold text-slate-700 dark:text-slate-300"></p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                <form id="status-form" method="POST" class="mt-1 flex gap-2">
                    @csrf @method('PATCH')
                    <select name="status" class="form-input py-1.5 text-sm w-full">
                        <option value="Applied">Applied</option>
                        <option value="Screening">Screening</option>
                        <option value="Interview">Interview</option>
                        <option value="Offer">Offer</option>
                        <option value="Hired">Hired</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <button type="submit" class="bg-brand-600 text-white px-3 rounded text-xs font-bold shadow-sm">Save</button>
                </form>
            </div>
        </div>
        <div class="px-6 pb-6">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Cover Letter</p>
            <div id="c-cover" class="bg-slate-50 dark:bg-slate-800 p-4 rounded-md text-sm text-slate-600 dark:text-slate-400 italic"></div>
        </div>
    </div>
</div>

<script>
    function openCandidateModal(candidate) {
        document.getElementById('c-name').textContent = candidate.first_name + ' ' + candidate.last_name;
        document.getElementById('c-job').textContent = candidate.job_posting.title;
        document.getElementById('c-email').textContent = candidate.email;
        document.getElementById('c-phone').textContent = candidate.phone || 'N/A';
        document.getElementById('c-cover').textContent = candidate.cover_letter || 'No cover letter provided.';
        
        document.querySelector('select[name="status"]').value = candidate.status;
        document.getElementById('status-form').action = `/admin/applicants/${candidate.id}/status`;
        
        document.getElementById('candidate-modal').classList.remove('hidden');
    }
</script>
@endsection
