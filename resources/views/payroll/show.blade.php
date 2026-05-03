@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Payroll Ledger • {{ $user->name }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Comprehensive history of salary disbursements and adjustments.</p>
        </div>
        <div class="mt-4 flex sm:ml-16 sm:mt-0 gap-3">
            @if(auth()->user()->hasAnyRole(['HR', 'Admin']) && auth()->id() !== $user->id)
            <button onclick="document.getElementById('draft-modal').classList.remove('hidden')" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-brand-600 dark:text-brand-400 shadow-sm border border-brand-200 dark:border-brand-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Generate Draft
            </button>
            <button onclick="document.getElementById('payroll-modal').classList.remove('hidden')" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                Manual Record
            </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-emerald-50 dark:bg-emerald-900/20 p-4 border border-emerald-200 dark:border-emerald-800">
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
            <p class="text-sm font-bold text-red-800 dark:text-red-400">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <div class="min-w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50">
                    <tr>
                        <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Period</th>
                        <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Gross Pay</th>
                        <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Deductions</th>
                        <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Net Amount</th>
                        <th class="py-3 px-6 text-center text-[10px] font-bold uppercase text-slate-500 tracking-wider">Status</th>
                        <th class="py-3 px-6 text-right text-[10px] font-bold uppercase text-slate-500 tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($records as $record)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-4 px-6 text-sm font-bold text-slate-900 dark:text-white uppercase">{{ $record->month }} {{ $record->year }}</td>
                        <td class="py-4 px-6 text-sm font-semibold text-slate-600 dark:text-slate-400">₱{{ number_format($record->gross_pay + $record->bonus, 2) }}</td>
                        <td class="py-4 px-6 text-sm font-semibold text-red-600 dark:text-red-400">-₱{{ number_format($record->deductions, 2) }}</td>
                        <td class="py-4 px-6 text-sm font-bold text-brand-600 dark:text-brand-400">₱{{ number_format($record->net_pay, 2) }}</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide border 
                                {{ $record->status === 'Paid' ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400' }}">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-3 items-center">
                                @if($record->status === 'Draft' && auth()->user()->hasAnyRole(['HR', 'Admin']))
                                <form action="{{ route('payroll.finalize', $record) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 uppercase tracking-tighter">Disburse</button>
                                </form>
                                <form action="{{ route('payroll.destroy', $record) }}" method="POST" onsubmit="return confirm('Completely remove this draft? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-600 uppercase tracking-tighter">Delete</button>
                                </form>
                                @endif
                                <button onclick="showBreakdown({{ $record->id }})" class="text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-tighter">Breakdown</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($records->isEmpty())
                    <tr>
                        <td colspan="4" class="py-16 text-center text-sm text-slate-400 italic">No payroll history recorded.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Payroll Modal -->
<div id="payroll-modal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Process Disbursement</h3>
            <button onclick="document.getElementById('payroll-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('payroll.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Month</label>
                    <select name="month" class="form-input">
                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                            <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Year</label>
                    <input type="number" name="year" value="{{ date('Y') }}" class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Gross Salary (₱)</label>
                <input type="number" name="gross_pay" step="0.01" required class="form-input" placeholder="0.00">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Bonus (₱)</label>
                    <input type="number" name="bonus" step="0.01" class="form-input" placeholder="0.00">
                </div>
                <div>
                    <label class="form-label">Deductions (₱)</label>
                    <input type="number" name="deductions" step="0.01" class="form-input" placeholder="0.00">
                </div>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-md border border-slate-100 dark:border-slate-800">
                <p class="text-[10px] font-bold uppercase text-slate-400 mb-1 tracking-widest">Auto Calculation</p>
                <p class="text-xs text-slate-500">Net Pay = Gross + Bonus - Deductions</p>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('payroll-modal').classList.add('hidden')" class="flex-1 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 bg-brand-600 text-white py-2 rounded-md text-sm font-bold shadow-sm hover:bg-brand-700 transition-colors uppercase tracking-wider">Disburse</button>
            </div>
        </form>
    </div>
</div>
<!-- Breakdown Modal -->
<div id="breakdown-modal" class="fixed inset-0 z-[120] hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:p-6" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-5xl max-h-[90vh] rounded-xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center shrink-0">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Salary Computation Breakdown</h3>
            <button onclick="document.getElementById('breakdown-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div id="breakdown-content" class="flex-1 overflow-y-auto p-0 bg-white dark:bg-slate-900">
            <div class="flex items-center justify-center py-20">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-600"></div>
            </div>
        </div>
    </div>
</div>

<script>
    async function showBreakdown(recordId) {
        const modal = document.getElementById('breakdown-modal');
        const content = document.getElementById('breakdown-content');
        
        // Show modal with loader
        modal.classList.remove('hidden');
        content.innerHTML = `
            <div class="flex flex-col items-center justify-center py-20 gap-4">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-slate-200 border-b-brand-600"></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Compiling Ledger Data...</p>
            </div>
        `;

        try {
            const response = await fetch(`/payroll/record/${recordId}/payslip`);
            const html = await response.text();
            
            // Extract only the body content from the returned HTML to avoid nested <html> tags
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const innerBody = doc.body.innerHTML;
            
            content.innerHTML = innerBody;
        } catch (error) {
            content.innerHTML = `
                <div class="p-10 text-center">
                    <p class="text-red-500 font-bold uppercase text-xs">Error loading breakdown. Please try again.</p>
                </div>
            `;
        }
    }
</script>
@endsection

<!-- Draft Modal -->
<div id="draft-modal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Generate Draft Payroll</h3>
            <button onclick="document.getElementById('draft-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('payroll.user-draft', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            <p class="text-xs text-slate-500 font-medium leading-relaxed italic">The engine will calculate this employee's salary factoring in Philippine statutory deductions (SSS, PhilHealth, Pag-IBIG) and attendance for the selected period.</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Period Month</label>
                    <select name="month" class="form-input">
                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                            <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Year</label>
                    <input type="number" name="year" value="{{ date('Y') }}" class="form-input">
                </div>
            </div>
            <button type="submit" class="w-full bg-brand-600 text-white py-3 rounded-md font-bold uppercase text-xs tracking-widest shadow-sm hover:bg-brand-700 transition-all">Calculate & Preview</button>
        </form>
    </div>
</div>
