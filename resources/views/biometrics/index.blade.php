@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Biometric Attendance</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage IoT devices and real-time attendance logs.</p>
        </div>
        <div class="mt-4 flex sm:ml-16 sm:mt-0 gap-3">
            <a href="{{ route('biometrics.mapping') }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 transition-colors">
                Employee Mapping
            </a>
            <a href="{{ route('biometrics.devices.create') }}" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                Register Device
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Devices List -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Active Devices</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($devices as $device)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $device->name }}</p>
                                <span class="h-1.5 w-1.5 rounded-full {{ $device->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 uppercase font-bold">{{ $device->device_id }} • {{ $device->location }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('biometrics.devices.edit', $device) }}" class="p-1 text-slate-400 hover:text-brand-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form action="{{ route('biometrics.devices.destroy', $device) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this device?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-slate-400 hover:text-red-600 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic text-center py-4">No devices registered.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Real-time Feed -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Live Attendance Feed</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-400 animate-pulse">LIVE</span>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                        <thead class="bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Employee</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Device</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase text-center">Auth</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase text-center">Status</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase">Time</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-feed" class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $log->user->name ?? 'Unknown (' . $log->employee_identifier . ')' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">{{ $log->device->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $log->auth_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase border 
                                        {{ $log->status === 'success' ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-red-100 bg-red-50 text-red-700' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs text-slate-500 font-mono">{{ $log->scanned_at->format('H:i:s') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple auto-refresh for the live feed every 10 seconds
    setInterval(() => {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newFeed = doc.getElementById('attendance-feed').innerHTML;
                document.getElementById('attendance-feed').innerHTML = newFeed;
            });
    }, 10000);
</script>
@endsection
