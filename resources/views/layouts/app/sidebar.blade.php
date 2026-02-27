<!DOCTYPE html>
<html :class="{ 'dark': theme === 'dark' }" class="h-full overflow-hidden"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ 
        {{-- theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'), --}}
        theme: localStorage.getItem('theme') || @js(auth()->user()->profile->preferences['theme'] ?? 'light'),
        isDesktop: window.innerWidth >= 768,
        
        init() {
            // Combine all initialization logic here
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    this.theme = e.matches ? 'dark' : 'light';
                    this.applyTheme();
                }
            });

            window.addEventListener('resize', () => {
                this.isDesktop = window.innerWidth >= 768;
            });

            window.addEventListener('theme-updated', event => {
                this.theme = event.detail.theme;
                localStorage.setItem('theme', this.theme);
                this.applyTheme();
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
    <body class="h-full overflow-hidden bg-base-100 antialiased">
        
        <x-dialog z-index="z-150" blur="sm" />
        <x-notifications />
        
        <div class="flex h-screen w-full overflow-hidden">
            <!-- Desktop View Sidebar-->
            <livewire:desktop-view-sidebar />
            <!-- Main Content : at the right -->
            <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
                <!-- Mobile View Sidebar : Hidden on Desktop View-->
                <div x-data="{ open:false }" class="md:hidden flex-shrink-0">
                    <livewire:header-view-profile />
                    <livewire:mobile-view-sidebar />
                </div>
                <div class="flex-1 flex flex-col min-h-0 mt-14 sm:mt-0 overflow-hidden">
                    {{ $slot }}
                </div>
            </main>
        </div>
        @livewireScripts
    </body>
</html>