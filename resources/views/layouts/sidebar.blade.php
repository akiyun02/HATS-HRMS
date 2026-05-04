@auth
        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

        <!-- Sidebar -->
        <div id="main-sidebar" class="fixed inset-y-0 z-50 flex w-72 flex-col translate-x-[-100%] lg:translate-x-0 transition-transform duration-200 ease-in-out">
            <div class="flex grow flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 h-full">
                <!-- Brand Header -->
                <div class="flex h-16 shrink-0 items-center border-b border-slate-100 dark:border-slate-800/50 px-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-brand-600 p-1.5 rounded-md shadow-sm">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">HATS <span class="text-brand-600">HRMS</span></span>
                    </div>
                </div>

                <!-- Main Scrollable Navigation -->
                <nav class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar">
                    <ul role="list" class="flex flex-col gap-y-7 h-full">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                        Dashboard
                                    </a>
                                </li>

                                @can('hr')
                                <li class="mt-4">
                                    <div class="text-xs font-bold leading-6 text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 mb-1">Management</div>
                                    <ul role="list" class="space-y-1">
                                        <li>
                                            <a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('employees.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                                </svg>
                                                Staff Directory
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('departments.index') }}" class="{{ request()->routeIs('departments.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('departments.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75H21m-3 3.75H21m-15 3.75H21" />
                                                </svg>
                                                Departments
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('leaves.admin') }}" class="{{ request()->routeIs('leaves.admin') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('leaves.admin') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                                Leaves
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('leave-policies.index') }}" class="{{ request()->routeIs('leave-policies.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('leave-policies.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.966 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                                                </svg>
                                                Leave Policies
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('recruitment.index') }}" class="{{ request()->routeIs('recruitment.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('recruitment.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 18.4V14.15m16.5 0a2.125 2.125 0 00-2.125-2.125H5.875A2.125 2.125 0 003.75 14.15m16.5 0a2.125 2.125 0 01-2.125 2.125H5.875a2.125 2.125 0 01-2.125-2.125M12 6.75v6m-3-3h6m-3-3c1.352 0 2.484.912 2.766 2.131.066.287.1.584.1.869H9.135c0-.285.033-.582.1-.869.282-1.219 1.414-2.131 2.766-2.131z" />
                                                </svg>
                                                Recruitment
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('payroll.admin.index') }}" class="{{ request()->routeIs('payroll.admin.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('payroll.admin.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Payroll
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('performance.admin') }}" class="{{ request()->routeIs('performance.admin') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('performance.admin') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01-.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                </svg>
                                                Performance
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('reports.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                                </svg>
                                                Reports
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('audit-logs.index') }}" class="{{ request()->routeIs('audit-logs.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('audit-logs.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                                </svg>
                                                Audit Logs
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('settings.*') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.57 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Settings
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan

                                <li class="mt-4">
                                    <div class="text-xs font-bold leading-6 text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 mb-1">Self Service</div>
                                    <ul role="list" class="space-y-1">
                                        <li>
                                            <a href="{{ route('attendance.index') }}" class="{{ request()->routeIs('attendance.index') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('attendance.index') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Attendance
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('leaves.index') }}" class="{{ request()->routeIs('leaves.index') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('leaves.index') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                                </svg>
                                                My Leaves
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('payroll.show', auth()->user()) }}" class="{{ request()->routeIs('payroll.show') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('payroll.show') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                My Payslips
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('performance.index') }}" class="{{ request()->routeIs('performance.index') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('performance.index') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01-.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                </svg>
                                                My Performance
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- Fixed User Section at Bottom -->
                        <li class="mt-auto pt-6 pb-4 border-t border-slate-200 dark:border-slate-800">
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'bg-slate-50 text-brand-700 dark:bg-slate-800 dark:text-brand-400 border-slate-200 dark:border-slate-700 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-brand-700 dark:hover:text-brand-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-transparent' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold border transition-all duration-200">
                                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('profile.show') ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 group-hover:text-brand-600 dark:group-hover:text-brand-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        My Profile
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="group flex w-full gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-slate-600 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-950/30 hover:text-red-600 dark:hover:text-red-400 transition-all border border-transparent">
                                            <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-red-600 dark:group-hover:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
@endauth
