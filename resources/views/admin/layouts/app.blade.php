<div>
    <!-- It always seems impossible until it is done. - Nelson Mandela -->
</div>
<!DOCTYPE html>
<html lang="en" class="antialiased">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hataiyan Admin Panel</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = { darkMode: 'class' };
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(6px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.25s ease-out;
        }
    </style>

    <!-- Theme init -->
    <script>
        (function () {
            const saved = localStorage.getItem('admin_theme');
            if (saved === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>

<body class="bg-gray-100 text-slate-800 dark:bg-slate-900 dark:text-slate-200">

    <!-- ============= ADMIN TOP NAVBAR ============= -->
    <header class="fixed top-0 left-0 right-0 z-50 
    bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl 
    border-b border-slate-200 dark:border-slate-700 shadow-sm">

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">

            {{-- Left: Brand --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <img src="/images/Hataiyan_logo_pink.png" 
                     class="h-10 w-10 object-contain rounded-lg shadow-sm group-hover:scale-105 transition" />

                <span class="font-semibold text-xl tracking-tight 
                             text-slate-800 dark:text-slate-200 group-hover:text-sky-600 dark:group-hover:text-sky-400">
                    Admin Panel
                </span>
            </a>

            {{-- Right: Actions --}}
            <div class="flex items-center gap-4">

                {{-- THEME TOGGLE --}}
                <button id="admin-theme-toggle" aria-label="Toggle theme"
                    class="h-9 w-9 flex items-center justify-center rounded-full 
                           bg-slate-100 dark:bg-slate-700
                           hover:bg-slate-200 dark:hover:bg-slate-600 
                           transition shadow-sm">

                    <span id="admin-theme-icon">
                        {{-- Default Icon (Filled Moon / Sun dynamic from JS) --}}
                        <svg class="w-5 h-5 text-slate-700 dark:text-slate-300" 
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M21 12.79A9 9 0 1111.21 3 
                                     7 7 0 0021 12.79z" />
                        </svg>
                    </span>
                </button>

                {{-- LOGGED ADMIN --}}
                @if(session()->has('admin'))
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button
                            class="px-4 py-2 rounded-lg text-sm font-medium
                                   bg-rose-50 text-rose-600 
                                   hover:bg-rose-100 
                                   dark:bg-rose-600/20 dark:text-rose-300 
                                   dark:hover:bg-rose-600/30 
                                   border border-rose-200 dark:border-rose-700 
                                   flex items-center gap-2 transition">

                            {{-- Heroicon: Arrow Left On Rectangle --}}
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.75 9V5.25a2.25 2.25 0 00-2.25-2.25h-6A2.25 
                                         2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 
                                         21h6a2.25 2.25 0 002.25-2.25V15M12 
                                         9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>

                            Logout
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>

</header>


    <!-- ============= LAYOUT ============= -->
    @if(session()->has('admin'))

        <!-- AUTHENTICATED ADMIN LAYOUT -->
        <div class="pt-16 flex">

            <!-- SIDEBAR -->
            <aside class="hidden md:block fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 
        overflow-y-auto bg-white dark:bg-slate-900 border-r border-slate-200 
        dark:border-slate-700 p-4">

                <nav class="space-y-1">

                    <h3 class="text-xs font-semibold text-slate-400 dark:text-slate-500 tracking-wider mb-3 px-3">
                        NAVIGATION
                    </h3>

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg group 
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300' : 'text-slate-700 dark:text-slate-300' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 group-hover:text-sky-600 dark:group-hover:text-sky-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                d="M3 12l9-9 9 9M4 10v10h5V14h6v6h5V10" />
                        </svg>

                        Dashboard
                    </a>

                    {{-- Manage Users --}}
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg group 
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition
                      {{ request()->routeIs('admin.users.*') ? 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300' : 'text-slate-700 dark:text-slate-300' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 group-hover:text-sky-600 dark:group-hover:text-sky-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                d="M5.5 21v-2a4.5 4.5 0 019 0v2M12 11a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>

                        Manage Users
                    </a>

                    {{-- Manage Posts --}}
                    <a href="{{ route('admin.posts.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg group 
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition
                      {{ request()->routeIs('admin.posts.*') ? 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300' : 'text-slate-700 dark:text-slate-300' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 group-hover:text-sky-600 dark:group-hover:text-sky-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                d="M4 6h16M4 12h16M4 18h10" />
                        </svg>

                        Manage Posts
                    </a>

                    {{-- Reports --}}
                    <a href="{{ route('admin.reports.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg group 
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition
                      {{ request()->routeIs('admin.reports.*') ? 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300' : 'text-slate-700 dark:text-slate-300' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 group-hover:text-sky-600 dark:group-hover:text-sky-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                d="M12 9v2m0 4h.01M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                        </svg>

                        Reports
                    </a>

                    {{-- Manage Admins --}}
                    <a href="{{ route('admin.admins.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg group 
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition
                      {{ request()->routeIs('admin.admins.*') ? 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300' : 'text-slate-700 dark:text-slate-300' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 group-hover:text-sky-600 dark:group-hover:text-sky-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 4v16m8-8H4" />
                        </svg>

                        Manage Admins
                    </a>

                </nav>

            </aside>


            <!-- MAIN CONTENT -->
            <main class="flex-1 md:ml-64 min-h-screen px-4 md:px-8 py-6">
                @yield('content')
            </main>

        </div>

    @else

        <!-- UNAUTHENTICATED (LOGIN SCREEN) -->
        <main class="min-h-screen flex-1 items-center justify-center px-4 py-16">
            @yield('content')
        </main>

    @endif


    <!-- ============= TOAST SUCCESS MESSAGE ============= -->
    @if(session('success'))
        <div class="fixed top-5 right-5 z-50 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg animate-slide-in">
            {{ session('success') }}
        </div>
    @endif

    <!-- ============= THEME TOGGLE SCRIPT ============= -->
    <script>
        (function () {
            const btn = document.getElementById("admin-theme-toggle");
            const icon = document.getElementById("admin-theme-icon");

            const updateIcon = () => {
                const isDark = document.documentElement.classList.contains('dark');
                icon.innerHTML = isDark
                    ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1M12 20v1M4.22 4.22l.7.7M18.36 18.36l.7.7M1 12h1M22 12h1M4.22 19.78l.7-.7M18.36 5.64l.7-.7M12 5a7 7 0 100 14 7 7 0 000-14z"/>
                       </svg>`
                    : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                       </svg>`;
            };

            updateIcon();

            btn.addEventListener("click", () => {
                const dark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('admin_theme', dark ? 'dark' : 'light');
                updateIcon();
            });
        })();
    </script>

</body>

</html>