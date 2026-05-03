<div class="p-6 sm:p-8 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Earnings Table -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                <div class="h-4 w-1 bg-emerald-500 rounded-full"></div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Earnings & Additions</h4>
            </div>
            <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-lg">
                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th scope="col" class="py-2 px-4 text-left text-[10px] font-bold uppercase text-slate-500">Description</th>
                            <th scope="col" class="py-2 px-4 text-right text-[10px] font-bold uppercase text-slate-500">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-900">
                        @foreach($record->lineItems->where('type', 'Addition') as $item)
                        <tr>
                            <td class="py-3 px-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $item->name }}
                                @if($item->percentage)
                                    <span class="ml-1 text-[10px] font-bold text-slate-400">({{ $item->percentage }}%)</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm font-bold text-slate-900 dark:text-white text-right">₱{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        @if($record->bonus > 0)
                        <tr class="bg-emerald-50/30 dark:bg-emerald-950/20">
                            <td class="py-3 px-4 text-sm font-bold text-emerald-700 dark:text-emerald-400">Statutory Bonus (13th Month)</td>
                            <td class="py-3 px-4 text-sm font-black text-emerald-700 dark:text-emerald-400 text-right">₱{{ number_format($record->bonus, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot class="bg-slate-50/50 dark:bg-slate-800/30">
                        <tr>
                            <td class="py-3 px-4 text-xs font-bold text-slate-500 uppercase">Total Earnings</td>
                            <td class="py-3 px-4 text-sm font-black text-slate-900 dark:text-white text-right">₱{{ number_format($record->gross_pay + $record->bonus, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Deductions Table -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                <div class="h-4 w-1 bg-red-500 rounded-full"></div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Statutory & Other Deductions</h4>
            </div>
            <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-lg">
                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th scope="col" class="py-2 px-4 text-left text-[10px] font-bold uppercase text-slate-500">Description</th>
                            <th scope="col" class="py-2 px-4 text-right text-[10px] font-bold uppercase text-slate-500">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-900">
                        @foreach($record->lineItems->where('type', 'Deduction') as $item)
                        <tr>
                            <td class="py-3 px-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $item->name }}
                                @if($item->percentage)
                                    <span class="ml-1 text-[10px] font-bold text-slate-400">({{ $item->percentage }}%)</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm font-bold text-red-600 dark:text-red-400 text-right">-₱{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50/50 dark:bg-slate-800/30">
                        <tr>
                            <td class="py-3 px-4 text-xs font-bold text-slate-500 uppercase">Total Deductions</td>
                            <td class="py-3 px-4 text-sm font-black text-red-600 dark:text-red-400 text-right">-₱{{ number_format($record->deductions, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-brand-600 rounded-xl p-6 text-white shadow-lg shadow-brand-500/20">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center border border-white/10 shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Net Take-Home Pay</p>
                    <p class="text-3xl font-black tracking-tighter">₱{{ number_format($record->net_pay, 2) }}</p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Status</p>
                <p class="text-sm font-bold uppercase">{{ $record->status }}</p>
            </div>
        </div>
    </div>
</div>
