<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>


<div 
    x-show="open" 
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full" 
    x-cloak
    @click.away="open = false"
    {{-- :class="{ open ? 'flex' : ''}" --}}
    class="absolute inset-0 w-64 h-screen shadow-lg z-50 transition-all duration-300 bg-base-200 dark:bg-base-900"
>
    <!-- Brand Logo -->
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-2">
            <div class="flex-shrink-0 w-8 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                </svg>
            </div>
            <span class="font-bold text-md whitespace-nowrap">Base App</span>
        </div>
        <!-- Toggle Button (shows only when hovered if closed) -->
        <button
            x-show="open"
            @click.stop="open = !open"
            x-transition.opacity
            class="absolute right-[-20px] flex items-center justify-center top-2 rounded text-base-content hover:bg-base-300 tooltip tooltip-right p-2" data-tip="Toggle Sidebar"
        >
            <!-- Slider Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 20" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path d="M9 3v18" />
            </svg>
        </button>
    </div>
    <!-- Navigations  -->
    <livewire:navigation />
</div>
