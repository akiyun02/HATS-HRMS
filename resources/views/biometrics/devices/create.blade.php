@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5 flex items-center gap-3">
        <a href="{{ route('biometrics.index') }}" class="text-slate-400 hover:text-brand-600 transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Register Biometric Device</h2>
    </div>

    <form action="{{ route('biometrics.devices.store') }}" method="POST" class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        @csrf
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Device Identifier (ID)</label>
                    <input type="text" name="device_id" required class="form-input" placeholder="e.g. ESP32_FRONT_DOOR">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Display Name</label>
                    <input type="text" name="name" required class="form-input" placeholder="e.g. Main Lobby Reader">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Installation Location</label>
                <input type="text" name="location" class="form-input" placeholder="e.g. Building A, 1st Floor">
            </div>
            
            <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 text-xs text-amber-700 dark:text-amber-400 font-medium leading-relaxed">
                        Registering a device will generate a unique <strong>API Token</strong>. You must copy this token and flash it to your ESP32 firmware for the device to authenticate.
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
            <a href="{{ route('biometrics.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">Cancel</a>
            <button type="submit" class="bg-brand-600 text-white px-6 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-brand-700 transition-colors">Register & Generate Token</button>
        </div>
    </form>
</div>
@endsection
