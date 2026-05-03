@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    activeCategory: 'workforce',
    loading: false,
    noData: false,
    showPreview: false,
    previewUrl: '',
    categories: [
        { id: 'workforce', name: 'Workforce' },
        { id: 'attendance', name: 'Attendance' },
        { id: 'leaves', name: 'Leaves' },
        { id: 'payroll', name: 'Payroll' },
        { id: 'performance', name: 'Performance' }
    ],
    filters: {
        start_date: '{{ now()->startOfMonth()->toDateString() }}',
        end_date: '{{ now()->toDateString() }}',
        department_id: '',
        user_id: ''
    },
    workforceData: { total: 0 },
    charts: {},

    async loadData() {
        this.loading = true;
        const params = new URLSearchParams(this.filters).toString();
        try {
            const response = await fetch(`/admin/reports/data/${this.activeCategory}?${params}`);
            if (!response.ok) throw new Error('API Error: ' + response.statusText);
            const data = await response.json();
            
            // Robust no-data check
            this.noData = !data || 
                (this.activeCategory === 'attendance' ? (!data.summary || data.summary.values.length === 0) : 
                (!data.values || data.values.length === 0));

            if (!this.noData) {
                this.renderChart(data);
                if (this.activeCategory === 'workforce') this.workforceData = data;
            }
        } catch (e) { 
            console.error('Failed to load report data', e); 
            this.noData = true; 
        } finally { 
            this.loading = false; 
        }
    },

    openPreview(type) {
        const params = new URLSearchParams({...this.filters, type: type, format: 'pdf', disposition: 'inline'}).toString();
        this.previewUrl = `/admin/reports/export?${params}`;
        this.showPreview = true;
    },

    exportData(format) {
        const params = new URLSearchParams({...this.filters, type: this.activeCategory, format: format}).toString();
        window.location.href = `/admin/reports/export?${params}`;
    },

    renderChart(data) {
        if (!data) return;
        
        if (this.charts[this.activeCategory]) {
            if (Array.isArray(this.charts[this.activeCategory])) this.charts[this.activeCategory].forEach(c => c.destroy());
            else this.charts[this.activeCategory].destroy();
        }
        
        this.$nextTick(() => {
            const common = { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 10, weight: 'bold' }, usePointStyle: true } } } };
            const canvas = document.getElementById(this.activeCategory === 'attendance' ? 'attendanceSummaryChart' : this.activeCategory + 'Chart');
            if (!canvas) return;

            try {
                if (this.activeCategory === 'workforce') {
                    this.charts.workforce = new Chart(canvas, { type: 'doughnut', data: { labels: data.labels, datasets: [{ data: data.values, backgroundColor: ['#4f46e5', '#8b5cf6', '#ec4899', '#f97316', '#10b981'], borderWidth: 0, cutout: '75%' }] }, options: common });
                } else if (this.activeCategory === 'attendance' && data.summary && data.trends) {
                    this.charts.attendance = [
                        new Chart(document.getElementById('attendanceSummaryChart'), { type: 'pie', data: { labels: data.summary.labels, datasets: [{ data: data.summary.values, backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }] }, options: common }),
                        new Chart(document.getElementById('attendanceTrendsChart'), { type: 'line', data: { labels: data.trends.labels, datasets: [{ label: 'Present', data: data.trends.values, borderColor: '#4f46e5', backgroundColor: '#4f46e520', fill: true, tension: 0.4 }] }, options: common })
                    ];
                } else if (this.activeCategory === 'leaves') {
                    this.charts.leaves = new Chart(canvas, { type: 'bar', data: { labels: data.labels, datasets: [{ label: 'Approved', data: data.values, backgroundColor: '#8b5cf6', borderRadius: 4 }] }, options: { ...common, indexAxis: 'y' } });
                } else if (this.activeCategory === 'payroll') {
                    this.charts.payroll = new Chart(canvas, { type: 'bar', data: { labels: data.labels, datasets: [{ label: 'Net (₱)', data: data.values, backgroundColor: '#4f46e5', borderRadius: 4 }] }, options: common });
                } else if (this.activeCategory === 'performance') {
                    this.charts.performance = new Chart(canvas, { type: 'polarArea', data: { labels: data.labels, datasets: [{ data: data.values, backgroundColor: ['#10b98190', '#4f46e590', '#f59e0b90', '#ef444490'], borderWidth: 0 }] }, options: common });
                }
            } catch (err) {
                console.error('Chart rendering failed', err);
            }
        });
    }
}" x-init="loadData()">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Reports</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 font-medium">Generate organizational insights and export data for business intelligence.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <div class="inline-flex rounded-md shadow-sm">
                <button type="button" @click="exportData('csv')" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-l-md hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors">CSV</button>
                <button type="button" @click="openPreview('overview')" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border-t border-b border-slate-300 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors">Preview Overview</button>
                <button type="button" @click="window.location.href = `/admin/reports/export?${new URLSearchParams({...filters, type: 'overview', format: 'pdf'}).toString()}`" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-brand-600 border border-brand-600 rounded-r-md hover:bg-brand-700 transition-colors shadow-sm">Download PDF</button>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Start</label>
                <input type="date" x-model="filters.start_date" class="form-input py-1.5 text-sm" @change="loadData()">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">End</label>
                <input type="date" x-model="filters.end_date" class="form-input py-1.5 text-sm" @change="loadData()">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Unit</label>
                <select x-model="filters.department_id" class="form-input py-1.5 text-sm" @change="loadData()">
                    <option value="">All Units</option>
                    @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Staff</label>
                <select x-model="filters.user_id" class="form-input py-1.5 text-sm" @change="loadData()">
                    <option value="">All Staff</option>
                    @foreach($employees as $emp) <option value="{{ $emp->id }}">{{ $emp->name }}</option> @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button @click="filters.department_id = ''; filters.user_id = ''; loadData()" class="w-full text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest pb-2.5">Clear</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1 space-y-1">
            <template x-for="cat in categories">
                <button @click="activeCategory = cat.id; loadData()" :class="activeCategory === cat.id ? 'bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-400 border-brand-200 dark:border-brand-800' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 border-slate-200 dark:border-slate-800'" class="w-full flex items-center justify-between px-4 py-3 rounded-lg border text-sm font-bold transition-all duration-200">
                    <span x-text="cat.name"></span>
                    <svg x-show="activeCategory === cat.id" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </button>
            </template>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden min-h-[400px] flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider" x-text="categories.find(c => c.id === activeCategory)?.name + ' Analysis'"></h3>
                    <button @click="openPreview(activeCategory)" class="text-[10px] font-bold text-brand-600 uppercase tracking-widest hover:underline">Quick Preview</button>
                </div>
                <div class="p-6 flex-1 relative">
                    <div x-show="loading" class="absolute inset-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-[1px] z-10"></div>
                    <div x-show="activeCategory === 'workforce'" class="h-64"><canvas id="workforceChart"></canvas></div>
                    <div x-show="activeCategory === 'attendance'" class="grid grid-cols-2 gap-4 h-64"><canvas id="attendanceSummaryChart"></canvas><canvas id="attendanceTrendsChart"></canvas></div>
                    <div x-show="activeCategory === 'leaves'" class="h-64"><canvas id="leavesChart"></canvas></div>
                    <div x-show="activeCategory === 'payroll'" class="h-64"><canvas id="payrollChart"></canvas></div>
                    <div x-show="activeCategory === 'performance'" class="h-64"><canvas id="performanceChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreview" x-cloak style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 w-full max-w-5xl h-[90vh] rounded-xl shadow-2xl flex flex-col overflow-hidden border border-slate-200 dark:border-slate-800" @click.away="showPreview = false">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Executive Report Preview</h3>
                <div class="flex items-center gap-4">
                    <a :href="previewUrl.replace('disposition=inline', 'disposition=attachment')" class="text-[10px] font-bold text-brand-600 uppercase tracking-widest hover:underline">Download instead</a>
                    <button @click="showPreview = false" class="p-2 text-slate-400 hover:text-slate-600 transition-colors"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
            </div>
            <div class="flex-1 bg-slate-100 dark:bg-slate-950 p-4 relative">
                <template x-if="showPreview">
                    <object :data="previewUrl" type="application/pdf" class="w-full h-full rounded bg-white shadow-sm border border-slate-200">
                        <div class="flex flex-col items-center justify-center h-full space-y-4">
                            <p class="text-slate-500 dark:text-slate-400">PDF preview is not supported by your browser.</p>
                            <a :href="previewUrl.replace('disposition=inline', 'disposition=attachment')" class="px-4 py-2 bg-brand-600 text-white rounded-lg text-sm font-bold">Download Report</a>
                        </div>
                    </object>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
