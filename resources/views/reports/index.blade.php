@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    activeCategory: 'workforce',
    loading: false,
    noData: false,
    showPreview: false,
    previewUrl: '',
    categories: [
        { id: 'workforce', name: 'Workforce', icon: `<svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' /></svg>` },
        { id: 'attendance', name: 'Attendance', icon: `<svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' /></svg>` },
        { id: 'leaves', name: 'Leaves', icon: `<svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' /></svg>` },
        { id: 'payroll', name: 'Payroll', icon: `<svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>` },
        { id: 'performance', name: 'Performance', icon: `<svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' /></svg>` }
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
            if (Array.isArray(this.charts[this.activeCategory])) {
                this.charts[this.activeCategory].forEach(c => c.destroy());
            } else {
                this.charts[this.activeCategory].destroy();
            }
        }
        
        this.$nextTick(() => {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#475569';
            const gridColor = isDark ? '#1e293b' : '#f1f5f9';
            
            const common = { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            color: textColor,
                            font: { size: 10, weight: 'bold' }, 
                            usePointStyle: true,
                            padding: 20
                        } 
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#ffffff',
                        titleColor: isDark ? '#f8fafc' : '#1e293b',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        usePointStyle: true
                    }
                } 
            };

            const scales = {
                x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 9 } } },
                y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 9 } } }
            };

            const canvas = document.getElementById(this.activeCategory === 'attendance' ? 'attendanceSummaryChart' : this.activeCategory + 'Chart');
            if (!canvas) return;

            try {
                if (this.activeCategory === 'workforce') {
                    this.charts.workforce = new Chart(canvas, { 
                        type: 'doughnut', 
                        data: { 
                            labels: data.labels, 
                            datasets: [{ 
                                data: data.values, 
                                backgroundColor: ['#4f46e5', '#8b5cf6', '#ec4899', '#f97316', '#10b981'], 
                                borderWidth: 0, 
                                cutout: '70%' 
                            }] 
                        }, 
                        options: common 
                    });
                } else if (this.activeCategory === 'attendance' && data.summary && data.trends) {
                    this.charts.attendance = [
                        new Chart(document.getElementById('attendanceSummaryChart'), { 
                            type: 'pie', 
                            data: { 
                                labels: data.summary.labels, 
                                datasets: [{ 
                                    data: data.summary.values, 
                                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], 
                                    borderWidth: 0 
                                }] 
                            }, 
                            options: common 
                        }),
                        new Chart(document.getElementById('attendanceTrendsChart'), { 
                            type: 'line', 
                            data: { 
                                labels: data.trends.labels, 
                                datasets: [{ 
                                    label: 'Attendance Count', 
                                    data: data.trends.values, 
                                    borderColor: '#4f46e5', 
                                    backgroundColor: isDark ? '#4f46e530' : '#4f46e510', 
                                    fill: true, 
                                    tension: 0.4,
                                    pointBackgroundColor: '#4f46e5',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4
                                }] 
                            }, 
                            options: { ...common, scales: scales } 
                        })
                    ];
                } else if (this.activeCategory === 'leaves') {
                    this.charts.leaves = new Chart(canvas, { 
                        type: 'bar', 
                        data: { 
                            labels: data.labels, 
                            datasets: [{ 
                                label: 'Approved Requests', 
                                data: data.values, 
                                backgroundColor: '#8b5cf6', 
                                borderRadius: 6,
                                barThickness: 20
                            }] 
                        }, 
                        options: { ...common, indexAxis: 'y', scales: scales } 
                    });
                } else if (this.activeCategory === 'payroll') {
                    this.charts.payroll = new Chart(canvas, { 
                        type: 'bar', 
                        data: { 
                            labels: data.labels, 
                            datasets: [{ 
                                label: 'Net Disbursement (₱)', 
                                data: data.values, 
                                backgroundColor: '#4f46e5', 
                                borderRadius: 6,
                                barThickness: 40
                            }] 
                        }, 
                        options: { ...common, scales: scales } 
                    });
                } else if (this.activeCategory === 'performance') {
                    this.charts.performance = new Chart(canvas, { 
                        type: 'polarArea', 
                        data: { 
                            labels: data.labels, 
                            datasets: [{ 
                                data: data.values, 
                                backgroundColor: ['#10b981a0', '#4f46e5a0', '#f59e0ba0', '#ef4444a0'], 
                                borderWidth: 0 
                            }] 
                        }, 
                        options: { 
                            ...common, 
                            scales: { 
                                r: { 
                                    grid: { color: gridColor }, 
                                    angleLines: { color: gridColor },
                                    pointLabels: { color: textColor, font: { size: 10, weight: 'bold' } },
                                    ticks: { backdropColor: 'transparent', color: textColor, font: { size: 8 } }
                                } 
                            } 
                        } 
                    });
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
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Business Intelligence</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 font-medium">Generate organizational insights and export data for executive review.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <div class="inline-flex rounded-md shadow-sm">
                <button type="button" @click="exportData('csv')" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-l-md hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors">CSV Export</button>
                <button type="button" @click="openPreview('overview')" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border-t border-b border-slate-300 hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors">Preview PDF</button>
                <button type="button" @click="window.location.href = `/admin/reports/export?${new URLSearchParams({...filters, type: 'overview', format: 'pdf'}).toString()}`" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-brand-600 border border-brand-600 rounded-r-md hover:bg-brand-700 transition-colors shadow-sm">Download All</button>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div>
                <label class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Period Start
                </label>
                <input type="date" x-model="filters.start_date" class="form-input py-2 text-sm rounded-lg" @change="loadData()">
            </div>
            <div>
                <label class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Period End
                </label>
                <input type="date" x-model="filters.end_date" class="form-input py-2 text-sm rounded-lg" @change="loadData()">
            </div>
            <div>
                <label class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Department
                </label>
                <select x-model="filters.department_id" class="form-input py-2 text-sm rounded-lg" @change="loadData()">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                </select>
            </div>
            <div>
                <label class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Employee
                </label>
                <select x-model="filters.user_id" class="form-input py-2 text-sm rounded-lg" @change="loadData()">
                    <option value="">All Staff</option>
                    @foreach($employees as $emp) <option value="{{ $emp->id }}">{{ $emp->name }}</option> @endforeach
                </select>
            </div>
            <div class="flex items-end pb-0.5">
                <button @click="filters.department_id = ''; filters.user_id = ''; loadData()" class="w-full py-2 px-4 text-[10px] font-black text-slate-400 hover:text-red-500 dark:hover:text-red-400 uppercase tracking-widest transition-all flex items-center justify-center gap-2 border border-dashed border-slate-200 dark:border-slate-800 rounded-lg hover:border-red-200 dark:hover:border-red-900/30 hover:bg-red-50 dark:hover:bg-red-950/20">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1 space-y-1">
            <template x-for="cat in categories">
                <button @click="activeCategory = cat.id; loadData()" 
                    :class="activeCategory === cat.id ? 'bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-400 border-brand-200 dark:border-brand-800 ring-1 ring-brand-500/20' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 border-slate-200 dark:border-slate-800'" 
                    class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl border text-sm font-bold transition-all duration-200 group">
                    <div :class="activeCategory === cat.id ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-slate-500'" x-html="cat.icon"></div>
                    <span x-text="cat.name" class="flex-1 text-left"></span>
                    <svg x-show="activeCategory === cat.id" class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </button>
            </template>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden min-h-[500px] flex flex-col transition-colors">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400" x-html="categories.find(c => c.id === activeCategory)?.icon"></div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider" x-text="categories.find(c => c.id === activeCategory)?.name + ' Distribution'"></h3>
                    </div>
                    <button @click="openPreview(activeCategory)" class="text-[10px] font-black text-brand-600 dark:text-brand-400 uppercase tracking-widest hover:underline transition-all flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644m11.963 12.014a1.012 1.012 0 010-.644M5.999 7.125V4.5a2.25 2.25 0 012.25-2.25h1.372c.516 0 1.012.202 1.381.562L13.125 5.25M12 9v6m3-3H9" /></svg>
                        Interactive Preview
                    </button>
                </div>
                <div class="p-10 flex-1 relative min-h-[400px]">
                    <div x-show="loading" class="absolute inset-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-[2px] z-20 flex items-center justify-center">
                        <div class="flex flex-col items-center">
                            <svg class="animate-spin h-8 w-8 text-brand-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Processing Data...</span>
                        </div>
                    </div>

                    <div x-show="!loading && noData" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 z-10">
                        <div class="h-16 w-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-300 dark:text-slate-600 mb-4 border border-slate-100 dark:border-slate-700/50">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Metric Threshold Not Met</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-[250px]">No recorded activities match the current filter criteria.</p>
                        <button @click="filters.department_id = ''; filters.user_id = ''; loadData()" class="mt-4 text-[10px] font-bold text-brand-600 uppercase tracking-widest hover:underline">Reset Selection</button>
                    </div>

                    <div x-show="!noData" class="h-full w-full">
                        <div x-show="activeCategory === 'workforce'" class="h-[400px]"><canvas id="workforceChart"></canvas></div>
                        <div x-show="activeCategory === 'attendance'" class="grid grid-cols-1 xl:grid-cols-2 gap-10 min-h-[400px]">
                            <div class="h-[400px]"><canvas id="attendanceSummaryChart"></canvas></div>
                            <div class="h-[400px]"><canvas id="attendanceTrendsChart"></canvas></div>
                        </div>
                        <div x-show="activeCategory === 'leaves'" class="h-[400px]"><canvas id="leavesChart"></canvas></div>
                        <div x-show="activeCategory === 'payroll'" class="h-[400px]"><canvas id="payrollChart"></canvas></div>
                        <div x-show="activeCategory === 'performance'" class="h-[400px]"><canvas id="performanceChart"></canvas></div>
                    </div>
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
