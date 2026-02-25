<?php

use Livewire\Component;
use Illuminate\Support\Facades\Route;

new class extends \Livewire\Component
{
    public $label = '';
    public $image = '';
    public $baseClass = 'flex items-center rounded transition-colors duration-300 p-2 h-6 ';
    public $tooltipClass = ' tooltip tooltip-right absolute';

};
?>

<div
    x-data="{ 
        isDesktop: window.innerWidth >= 768,
        init() {
            window.addEventListener('resize', () => {
                this.isDesktop = window.innerWidth >= 768;
            });
        }
    }"
    :class="(isDesktop && collapse) ? ' justify-center ' : ' overflow-x-hidden'"
    class="h-10 flex items-center space-x-10 px-3 justify-between max-w-full cursor-pointer hover:text-base-900"
>
    <div class="flex items-center space-x-2 w-2/3 pl-2">    
        <div
            :class="isDesktop && collapse ? '{{ $tooltipClass }}' : ''"
            class="flex items-center justify-center flex-shrink-0 size-7 whitespace-nowrap text-sm"
            data-tip="User Profile"
        >
            @if($image)
                    {!! $image !!} 
            @else
                <img class="rounded-full" src="https://img.daisyui.com/images/profile/demo/distracted1@192.webp" />
            @endif
        </div>
        <span 
            class="whitespace-nowrap text-sm font-medium"
            x-cloak 
            x-show="!isDesktop || (isDesktop && !collapse)"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            {{ $label }}
        </span>
    </div>
    <div x-show="isDesktop && !collapse" class="w-1/3 flex items-end">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
            <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd" />
        </svg>
    </div>
</div>