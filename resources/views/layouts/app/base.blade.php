<!DOCTYPE html>
<html :class="{ 'dark': theme === 'dark' }" class="h-full overflow-hidden"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ 
        theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
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
        <x-notifications z-index="z-50" /> {{-- Add this! --}}
        <div class="flex h-screen w-full overflow-hidden">
            <div class="flex-1 flex flex-col min-h-0 mt-14 sm:mt-0 overflow-hidden">
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
    </body>
</html>