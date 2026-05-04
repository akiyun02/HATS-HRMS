@extends('layouts.app')

@section('content')
<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-50 dark:bg-[#09090b]">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="bg-brand-600 p-3 rounded-md shadow-sm">
                <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-slate-900 dark:text-white uppercase">Portal Login</h2>
        <p class="mt-2 text-center text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
            Enterprise Resource Management
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-slate-900 py-8 px-4 shadow-sm border border-slate-200 dark:border-slate-800 sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="form-label">Email Identification</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required class="form-input" placeholder="e.g. staff@company.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-600 font-bold uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="form-label">Secure Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="form-input" placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-red-600 font-bold uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 dark:bg-slate-800 cursor-pointer">
                    <label for="remember-me" class="ml-2 block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider cursor-pointer">Stay authenticated</label>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-brand-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-brand-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all uppercase tracking-wide">
                        Login
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    Want to join our team?
                </p>
                <a href="{{ route('careers.index') }}" class="mt-2 inline-flex items-center text-sm font-bold text-brand-600 hover:text-brand-500 transition-colors">
                    Browse Job Openings
                    <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
        
        <p class="mt-10 text-center text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-600">
            &copy; {{ date('Y') }} HATS Global Systems
        </p>
    </div>
</div>
@endsection
