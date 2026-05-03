<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HATS HRMS • Professional Workplace Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="h-full antialiased bg-slate-50 dark:bg-[#09090b] text-slate-900 dark:text-white transition-colors duration-300">
    <div class="relative min-h-full flex flex-col justify-center overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 z-0 opacity-[0.05]" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 32px 32px;"></div>

        <div class="relative z-10 container mx-auto px-6 py-20 text-center">
            <div class="inline-flex items-center rounded-md bg-white dark:bg-slate-900 px-3 py-1 text-xs font-bold text-brand-600 dark:text-brand-400 mb-10 border border-slate-200 dark:border-slate-800 shadow-sm uppercase tracking-wider">
                Enterprise Resource Planning
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-slate-900 dark:text-white mb-6 uppercase">
                HATS <span class="text-brand-600">HRMS</span>
            </h1>
            <p class="max-w-2xl mx-auto text-lg md:text-xl font-medium text-slate-500 dark:text-slate-400 leading-relaxed mb-12">
                A unified platform for workforce management, attendance tracking, and organizational intelligence. Streamline your HR operations with precision and security.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto rounded-md bg-brand-600 px-10 py-4 text-base font-bold text-white shadow-sm hover:bg-brand-700 transition-all uppercase tracking-wide">
                    Access Portal
                </a>
                <a href="#" class="w-full sm:w-auto rounded-md bg-white dark:bg-slate-900 px-10 py-4 text-base font-bold text-slate-900 dark:text-white shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all uppercase tracking-wide">
                    Documentation
                </a>
            </div>

            <div class="mt-20 flex flex-wrap items-center justify-center gap-10 text-slate-400 dark:text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider">Secure Access</span>
                <span class="text-xs font-bold uppercase tracking-wider">Audit Ready</span>
                <span class="text-xs font-bold uppercase tracking-wider">Real-time Data</span>
            </div>
        </div>

        <footer class="relative z-10 mt-auto py-10 text-center">
            <p class="text-xs font-medium text-slate-400 dark:text-slate-600">
                &copy; {{ date('Y') }} HATS Global Systems. All rights reserved.
            </p>
        </footer>
    </div>
</body>
</html>
