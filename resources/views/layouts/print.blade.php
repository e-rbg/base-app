<!DOCTYPE html>
<html :class="{ 'dark': theme === 'dark' }"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        theme: localStorage.getItem('theme') || 'light',
        init() {
            this.applyTheme();
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    this.theme = e.matches ? 'dark' : 'light';
                    this.applyTheme();
                }
            });
        },
        applyTheme() {
            document.documentElement.setAttribute('data-theme', this.theme);
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        }
    }"
    :data-theme="theme"
>
    <head>
        @include('partials.head')
        <style>
            @page { size: A4 portrait; margin: 0.5in; }
            @media print {
                html, body { margin: 0; padding: 0; }
            }
        </style>
    </head>
    <body class="bg-base-100 antialiased">
        <x-dialog z-index="z-50" blur="sm" />
        <x-notifications z-index="z-50" />
        {{ $slot }}
        @livewireScripts
    </body>
</html>
