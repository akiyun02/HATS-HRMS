@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <a href="{{ route('careers.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 transition-colors group">
            <svg class="mr-2 h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to All Openings
        </a>

        @auth
            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg bg-white dark:bg-slate-800 px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
        @endauth
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-12">
        <div class="p-8 sm:p-12">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-2 py-0.5 rounded bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-[10px] font-bold uppercase tracking-widest border border-brand-100 dark:border-brand-500/20">
                    Full-Time
                </span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-tight">Posted {{ $job->created_at->format('M d, Y') }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl mb-6">
                {{ $job->title }}
            </h1>
            
            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 leading-relaxed">
                {!! nl2br(e($job->description)) !!}
            </div>
        </div>
    </div>

    <div id="apply-form" class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 sm:p-12">
        <div class="max-w-2xl mx-auto">
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Apply for this position</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Complete the form below to submit your application.</p>
            </div>

            <form action="{{ route('careers.apply', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- Honeypot Field --}}
                <div style="display: none !important;">
                    <input type="text" name="hp_field" tabindex="-1" autocomplete="off">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">First Name</label>
                        <input type="text" name="first_name" id="first_name" required value="{{ old('first_name') }}" class="form-input w-full bg-white dark:bg-slate-900">
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Last Name</label>
                        <input type="text" name="last_name" id="last_name" required value="{{ old('last_name') }}" class="form-input w-full bg-white dark:bg-slate-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" class="form-input w-full bg-white dark:bg-slate-900">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-input w-full bg-white dark:bg-slate-900" placeholder="+1 (555) 000-0000">
                    </div>
                </div>

                <div>
                    <label for="resume" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Resume / CV (PDF or DOC)</label>
                    <div class="mt-1 flex justify-center border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-lg bg-white dark:bg-slate-900 hover:border-brand-500 transition-colors overflow-hidden">
                        <input id="resume" name="resume" type="file" class="sr-only" required accept=".pdf,.doc,.docx" onchange="updateFileName(this)">
                        <div class="w-full">
                            <label for="resume" id="upload-placeholder" class="flex flex-col items-center justify-center py-10 cursor-pointer w-full">
                                <svg class="h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 dark:text-slate-400 mt-2">
                                    <span class="font-bold text-brand-600 hover:text-brand-500">Upload a file</span>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">PDF, DOC up to 2MB</p>
                            </label>
                            
                            <div id="filename-display" class="hidden flex flex-col items-center justify-center py-10 w-full">
                                <svg class="h-12 w-12 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p id="selected-filename" class="mt-2 text-sm font-bold text-slate-900 dark:text-white"></p>
                                <button type="button" onclick="resetFile()" class="mt-1 text-[10px] font-bold text-red-500 uppercase tracking-widest hover:underline">Change File</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="cover_letter" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Cover Letter (Optional)</label>
                    <textarea name="cover_letter" id="cover_letter" rows="5" class="form-input w-full bg-white dark:bg-slate-900" placeholder="Tell us why you're a great fit...">{{ old('cover_letter') }}</textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-brand-600 px-6 py-4 text-sm font-bold text-white shadow-lg hover:bg-brand-700 transition-all hover:-translate-y-0.5">
                        Submit Application
                        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                    <p class="mt-4 text-center text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold">
                        By submitting, you agree to our <a href="#" class="underline">Privacy Policy</a> and <a href="#" class="underline">Terms</a>.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const display = document.getElementById('filename-display');
        const nameText = document.getElementById('selected-filename');
        
        if (input.files && input.files[0]) {
            nameText.textContent = input.files[0].name;
            placeholder.classList.add('hidden');
            display.classList.remove('hidden');
        }
    }

    function resetFile() {
        const input = document.getElementById('resume');
        const placeholder = document.getElementById('upload-placeholder');
        const display = document.getElementById('filename-display');
        
        input.value = '';
        placeholder.classList.remove('hidden');
        display.classList.add('hidden');
        
        // Automatically open the file picker
        input.click();
    }
</script>
@endsection
