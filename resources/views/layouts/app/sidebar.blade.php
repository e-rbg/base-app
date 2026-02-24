<!DOCTYPE html>
<html :class="{ 'dark': theme === 'dark' }"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ 
        theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        
        init() {
            // Listen for OS theme changes (e.g., sunset/sunrise auto-switch)
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    this.theme = e.matches ? 'dark' : 'light';
                    this.applyTheme();
                }
            });
            this.applyTheme();
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            this.applyTheme();
        },

        applyTheme() {
            document.documentElement.setAttribute('data-theme', this.theme);
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }"
    :data-theme="theme"
>
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-base-100 text-base-content transition-colors duration-300 antialiased">
        <div class="flex h-screen">
            <!-- Desktop View Sidebar-->
            <livewire:desktop-view-sidebar/>
            <!-- Main Content : at the right -->
            <main class="sm:px-10 sm:pt-5 w-full relative">
                <!-- Mobile View-->
                <div x-data="{ open:false }" class="sm:hidden mb-4 relative">
                    <!-- Mobile Header : Hidden in Desktop View, Visible in Mobile Devices -->
                    <livewire:header-view-profile />
                    <!-- Mobile Sidebar : Hidden in Despktop and Mobile View, can be triggered in mobile view by clicking the hamburger icon-->
                    <livewire:mobile-view-sidebar />
                    <!-- End Mobile Sidebar -->
                </div>
                <div>
                    {{ $slot }}
                </div>
            </main>
        </div>
        @livewireScripts
    </body>
</html>


<path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z" clip-rule="evenodd" />

