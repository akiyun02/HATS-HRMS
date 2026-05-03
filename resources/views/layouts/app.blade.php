<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HATS HRMS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('theme');
                const supportDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (theme === 'dark' || (!theme && supportDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="h-full antialiased transition-colors duration-300">
    @auth
        @include('layouts.sidebar')

        <div class="lg:pl-72 flex flex-col min-h-full transition-all duration-200">
            <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#09090b] px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 transition-colors">
                <button id="sidebar-toggle" type="button" class="-m-2.5 p-2.5 text-slate-700 dark:text-slate-300 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 items-center justify-end">
                    <!-- Notifications Dropdown -->
                    <div class="relative" id="notifications-wrapper">
                        <button id="notifications-toggle" type="button" class="p-2 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-brand-600 transition-all relative border border-slate-200 dark:border-slate-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-600 ring-2 ring-white dark:ring-slate-900"></span>
                            @endif
                        </button>

                        <div id="notifications-dropdown" class="hidden absolute right-0 mt-3 w-80 rounded-lg bg-white dark:bg-slate-900 shadow-xl border border-slate-200 dark:border-slate-800 z-[100] overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Notifications</h3>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-brand-600 uppercase">Clear All</button>
                                </form>
                                @endif
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-50 dark:border-slate-800 last:border-0">
                                    <p class="text-xs font-bold text-slate-900 dark:text-white leading-relaxed">{{ $notification->data['message'] }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                                        <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[9px] font-black text-brand-500 uppercase tracking-widest">Mark read</button>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <div class="p-10 text-center">
                                    <p class="text-xs text-slate-400 font-medium uppercase tracking-widest italic">All caught up!</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Theme Toggle Icon -->
                    <button id="theme-toggle-btn" type="button" class="p-2 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-brand-600 transition-all border border-slate-200 dark:border-slate-700">
                        <svg id="theme-icon-sun" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1m9-9h1M3 9h1m12.727-3.273l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                        </svg>
                        <svg id="theme-icon-moon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-200 dark:lg:bg-slate-800" aria-hidden="true"></div>
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <span class="block text-sm font-bold text-slate-900 dark:text-white leading-tight">{{ Auth::user()->name }}</span>
                                <span class="block text-[10px] font-bold text-brand-600 uppercase tracking-wider">{{ Auth::user()->roles->first()?->name }}</span>
                            </div>
                            <div class="h-9 w-9 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-sm shadow-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 py-8">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Global Alerts -->
                    @if (session('success'))
                        <div id="alert-success" class="mb-6 flex items-center p-3 text-emerald-800 rounded-md bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 shadow-sm transition-all duration-500 translate-y-0 opacity-100">
                            <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-md focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-100 inline-flex h-8 w-8 dark:bg-transparent dark:text-emerald-400 dark:hover:bg-emerald-500/20" onclick="document.getElementById('alert-success').remove()">
                                <span class="sr-only">Close</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                            </button>
                        </div>
                        <script>
                            setTimeout(() => {
                                const alert = document.getElementById('alert-success');
                                if(alert) {
                                    alert.classList.add('opacity-0', '-translate-y-4');
                                    setTimeout(() => alert.remove(), 500);
                                }
                            }, 5000);
                        </script>
                    @endif

                    @if (session('error') || $errors->any())
                        <div id="alert-error" class="mb-6 flex flex-col p-3 text-red-800 rounded-md bg-red-50 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20 shadow-sm transition-all duration-500">
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-bold">
                                    {{ session('error') ?? 'Submission Failed' }}
                                </span>
                                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-md focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex h-8 w-8 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-500/20" onclick="document.getElementById('alert-error').remove()">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                </button>
                            </div>
                            @if($errors->any())
                                <ul class="mt-2 ml-7 list-disc list-inside text-xs font-semibold opacity-80">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <!-- Breadcrumbs -->
                    <nav class="flex mb-6 overflow-x-auto pb-1 whitespace-nowrap no-scrollbar border-b border-slate-100 dark:border-slate-800" aria-label="Breadcrumb">
                        <ol role="list" class="flex items-center space-x-2">
                            <li>
                                <div>
                                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                                        <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span class="sr-only">Home</span>
                                    </a>
                                </div>
                            </li>
                            @yield('breadcrumbs')
                        </ol>
                    </nav>

                    @yield('content')
                </div>
            </main>
            
            <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 py-4 px-4 sm:px-6 lg:px-8 text-center text-xs font-medium text-slate-500 dark:text-slate-400">
                &copy; {{ date('Y') }} HATS HRMS Portal. All rights reserved.
            </footer>
        </div>
    @else
        <main class="h-full">
            @yield('content')
        </main>
    @endauth

    <script>
        function updateThemeUI() {
            const isDark = document.documentElement.classList.contains('dark');
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            
            if(isDark) {
                sunIcon?.classList.remove('hidden');
                moonIcon?.classList.add('hidden');
            } else {
                sunIcon?.classList.add('hidden');
                moonIcon?.classList.remove('hidden');
            }
        }

        const themeBtn = document.getElementById('theme-toggle-btn');
        if(themeBtn) {
            themeBtn.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateThemeUI();
            });
        }

        // Initialize icons
        updateThemeUI();

        const backdrop = document.getElementById('sidebar-backdrop');
        const sidebar = document.getElementById('main-sidebar');
        const toggle = document.getElementById('sidebar-toggle');

        if(toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.remove('translate-x-[-100%]');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                }, 10);
            });
        }

        if(backdrop) {
            backdrop.addEventListener('click', () => {
                sidebar.classList.add('translate-x-[-100%]');
                backdrop.classList.add('opacity-0');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            });
        }

        // Notification Toggle
        const notifToggle = document.getElementById('notifications-toggle');
        const notifDropdown = document.getElementById('notifications-dropdown');
        if(notifToggle) {
            notifToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
            });
        }
        document.addEventListener('click', (e) => {
            if (notifDropdown && !notifDropdown.contains(e.target) && !notifToggle.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
