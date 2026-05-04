@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5 flex items-center gap-3">
        <a href="{{ route('biometrics.index') }}" class="text-slate-400 hover:text-brand-600 transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Edit Biometric Device</h2>
    </div>

    <form action="{{ route('biometrics.devices.update', $device) }}" method="POST" class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Device Identifier (ID)</label>
                    <input type="text" name="device_id" value="{{ $device->device_id }}" required class="form-input" placeholder="e.g. ESP32_FRONT_DOOR">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Display Name</label>
                    <input type="text" name="name" value="{{ $device->name }}" required class="form-input" placeholder="e.g. Main Lobby Reader">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Installation Location</label>
                <input type="text" name="location" value="{{ $device->location }}" class="form-input" placeholder="e.g. Building A, 1st Floor">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Device Status</label>
                <select name="is_active" class="form-input">
                    <option value="1" {{ $device->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$device->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Current API Token</p>
                        <p class="mt-1 font-mono text-sm text-slate-900 dark:text-white break-all">{{ $device->api_token }}</p>
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $device->api_token }}')" class="ml-4 text-xs font-bold text-brand-600 uppercase tracking-tighter hover:text-brand-700">Copy</button>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
            <a href="{{ route('biometrics.index') }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">Cancel</a>
            <button type="submit" class="bg-brand-600 text-white px-6 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-brand-700 transition-colors">Save Changes</button>
        </div>
    </form>
</div>
@endsection
