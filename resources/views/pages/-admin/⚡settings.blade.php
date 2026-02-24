<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Admin Settings')] class extends Component
{
    //
};
?>

<div>
    Settings
    <div class="">
        <div class="px-3 py-2">
            <button 
                @click="toggleTheme()" 
                class="flex items-center w-full h-10 px-2 rounded-lg transition-colors hover:bg-base-200 text-base-content/70 hover:text-base-content"
            >
                <div class="flex items-center justify-center flex-shrink-0 size-6">
                    <svg x-show="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.966-8.966h-2.25M4.113 12.049H1.863m2.723-8.146l1.591 1.591m12.932 12.932l1.591 1.591m0-16.114l-1.591 1.591M6.437 17.563l-1.591 1.591M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    
                    <svg x-show="theme === 'light'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </div>

                <span 
                     
                    x-cloak
                    class="ml-4 text-sm font-medium"
                >
                    <span x-text="theme === 'light' ? 'Dark Mode' : 'Light Mode'"></span>
                </span>
            </button>
        </div>

    </div>
    <button 
        @click="localStorage.removeItem('theme'); location.reload();" 
        class="btn btn-xs btn-outline"
    >
        Follow System Settings
    </button>
</div>