<!DOCTYPE html>
<html lang="en" class="antialiased">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hataiyan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = { darkMode: 'class' };

    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .infinite-loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 0;
            color: #6c757d;
            font-size: 14px;
            animation: fadeIn 0.3s ease-in-out;
            cursor: pointer;
        }

        .loader {
            width: 28px;
            height: 28px;
            border: 3px solid #ccc;
            border-top-color: #3490dc;
            /* primary blue */
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-bottom: 6px;
        }

        /* Animation: Spin */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Animation: Fade in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>


    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            if (saved === 'dark') document.documentElement.classList.add('dark');
            if (saved === ' ') document.documentElement.classList.remove('dark');
        })();
    </script>

    @livewireStyles
</head>

<body class="bg-white text-slate-800 dark:bg-slate-800 dark:text-slate-200">

    {{-- NAVBAR (fixed) --}}
    <header
        class="fixed top-0 left-0 right-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <img src="/images/Hataiyan_logo_pink.png" alt="Hataiyan" class="h-10 w-10 object-contain">
                    <span class="font-semibold text-lg">Hataiyan</span>
                </a>

                <div class="flex items-center gap-3">
                    {{-- Theme toggle --}}
                    <button id="theme-toggle" aria-label="Toggle theme"
                        class="h-9 px-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <span id="theme-icon" class="inline-block">
                            {{-- Moon icon (default) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12.79A9 9 0 0111.21 3 7 7 0 0021 12.79z" />
                            </svg>
                        </span>
                    </button>

                    @if(!session()->has('user'))
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            {{-- Login icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 17l5-5-5-5v10z" />
                            </svg>
                            <span class="text-sm">Login</span>
                        </a>

                        <a href="/register"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-slate-900 text-white dark:bg-sky-500 dark:text-white hover:opacity-95 transition">
                            {{-- User plus icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 8v6M22 11h-6" />
                            </svg>
                            <span class="text-sm">Sign up</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- Success toast (top-right) --}}
    @if(session('success'))
        <div id="success-toast"
            class="fixed top-5 right-5 z-50 flex items-center max-w-sm w-full bg-green-600 text-white rounded-lg shadow-lg animate-slide-in p-4"
            role="alert">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0 text-white mr-3" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m6 2a9 9 0 1 1-18 0a9 9 0 0 1 18 0z" />
            </svg>

            <!-- Message -->
            <span class="flex-1 text-sm font-medium">
                {{ session('success') }}
            </span>

            <!-- Close Button -->
            <button type="button" class="ml-3 text-white hover:text-gray-200 focus:outline-none"
                onclick="document.getElementById('success-toast').classList.add('hidden')">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Small Tailwind animation -->
        <style>
            @keyframes slideIn {
                0% {
                    opacity: 0;
                    transform: translateX(1rem);
                }

                100% {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .animate-slide-in {
                animation: slideIn 0.3s ease-out;
            }
        </style>

        <!-- Optional auto-hide -->
        <script>
            setTimeout(() => {
                const toast = document.getElementById('success-toast');
                if (toast) toast.classList.add('hidden');
            }, 4000);
        </script>
    @endif


    {{-- MAIN LAYOUT: left sidebar (fixed), center scrollable, right suggestions (fixed) --}}
    <div class="pt-16"> {{-- push content below fixed navbar (h-16) --}}

        @if(session()->has('user'))
            {{-- Left sidebar (fixed on md+) --}}
            <aside
                class="hidden md:block fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 overflow-auto p-4  bg-white dark:bg-gray-900">
                <div class="bg-white/0 dark:bg-transparent">
                    @include('components.sidebar')
                </div>
            </aside>

           
            {{-- CENTER CONTENT: make it responsive: full width on small screens, and centered with side margins on md+ --}}
            <main class=" md:ml-64 md:mr-72  flex items-center justify-center ">
                <div class="w-full px-2 py-4 ">
                    @yield('content')
                </div>
            </main>

        @else
            {{-- Guest full-screen centered content --}}
            <div class="min-h-[calc(100vh-8rem)] flex items-center justify-center px-4">
                <div class="">
                    @yield('content')
                </div>
            </div>
        @endif

    </div>

    @livewireScripts
    {{-- Theme toggle script (updates documentElement.classList) --}}
    <script>
        (function () {
            const toggle = document.getElementById('theme-toggle');
            const iconWrap = document.getElementById('theme-icon');

            function setIcon(isDark) {
                iconWrap.innerHTML = isDark
                    ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1M12 20v1M4.22 4.22l.7.7M18.36 18.36l.7.7M1 12h1M22 12h1M4.22 19.78l.7-.7M18.36 5.64l.7-.7M12 5a7 7 0 100 14 7 7 0 000-14z"/>
             </svg>`
                    : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
             </svg>`;
            }

            // init
            const saved = localStorage.getItem('theme');
            const isDark = saved === 'dark' || (!saved && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) document.documentElement.classList.add('dark');
            setIcon(isDark);

            toggle?.addEventListener('click', () => {
                const dark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', dark ? 'dark' : 'light');
                setIcon(dark);
            });
        })();
    </script>

    {{-- Optional: small script to auto-hide success toast after 3s --}}
    <script>
        (function () {
            const toast = document.querySelector('[data-toast]');
            if (toast) {
                setTimeout(() => toast.remove(), 3000);
            }
        })();
    </script>

</body>

</html>