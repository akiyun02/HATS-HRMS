@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{ 
    userId: '', 
    rfid: '', 
    fingerprint: '',
    fillForm(mapping) {
        this.userId = mapping.user_id;
        this.rfid = mapping.rfid_uid || '';
        this.fingerprint = mapping.fingerprint_id || '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}">
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5 flex items-center gap-3">
        <a href="{{ route('biometrics.index') }}" class="text-slate-400 hover:text-brand-600 transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Employee Biometric Mapping</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Mapping Form -->
        <div class="lg:col-span-1">
            <form action="{{ route('biometrics.mapping.store') }}" method="POST" class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg p-6 space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Select Employee</label>
                    <select name="user_id" required class="form-input" x-model="userId">
                        <option value="">Choose an employee...</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employeeProfile?->employee_id ?? 'No ID' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">RFID Tag UID</label>
                    <input type="text" name="rfid_uid" class="form-input" placeholder="e.g. 1A2B3C4D" x-model="rfid">
                    <p class="mt-1 text-[9px] text-slate-400 font-bold uppercase italic">Found on the card/fob</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-2">Fingerprint Slot ID</label>
                    <input type="number" name="fingerprint_id" min="1" max="127" class="form-input" placeholder="1-127" x-model="fingerprint">
                    <p class="mt-1 text-[9px] text-slate-400 font-bold uppercase italic">As stored in the AS608 memory</p>
                </div>
                <button type="submit" class="w-full bg-brand-600 text-white py-3 rounded-md text-sm font-bold shadow-sm hover:bg-brand-700 transition-all hover:-translate-y-0.5">
                    Save Credentials
                </button>
                <button type="button" @click="userId = ''; rfid = ''; fingerprint = '';" class="w-full text-slate-500 text-[10px] font-bold uppercase tracking-widest hover:text-slate-700 transition-colors" x-show="userId">
                    Clear Selection
                </button>
            </form>
        </div>

        <!-- Current Mappings Table -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase">Employee</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase">RFID UID</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase text-center">F-Print Slot</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @foreach($mappings as $mapping)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white">{{ $mapping->user->name }}</td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $mapping->rfid_uid ?? '---' }}</td>
                            <td class="px-6 py-4 text-center text-xs font-bold text-brand-600">{{ $mapping->fingerprint_id ?? '---' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="fillForm({{ $mapping }})" class="text-slate-400 hover:text-brand-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form action="{{ route('biometrics.mapping.destroy', $mapping) }}" method="POST" onsubmit="return confirm('Remove this mapping?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
