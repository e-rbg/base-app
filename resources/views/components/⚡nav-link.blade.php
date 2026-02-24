<?php

use Livewire\Component;
use Illuminate\Support\Facades\Route;

new class extends \Livewire\Component
{
    public $url = '#';
    public $label = '';
    public $icon = '';
    public $activeClass = 'text-base-900 font-semibold';
    public $baseClass = 'flex items-center rounded transition-colors p-2 h-6 hover:text-base-900';
    public $tooltipClass = ' tooltip tooltip-right ';
    public $forceShowLabel = false;

    public function getResolvedUrlProperty(): string
    {
        return Route::has($this->url) ? route($this->url) : url($this->url);
    }

    public function isActive(): bool
    {
        return request()->fullUrlIs($this->resolved_url);
    }
   
};
?>

<a 
    href="{{ $this->resolved_url }}" 
    wire:navigate
    @click.stop
    x-data="{ 
        isDesktop: window.innerWidth >= 768,
        forceShow: @js($forceShowLabel == true),
        init() {
            window.addEventListener('resize', () => {
                this.isDesktop = window.innerWidth >= 768;
            });
        }
    }"
    
    {{ $attributes->merge(['class' => $this->isActive() ? "$baseClass $activeClass" : $baseClass ]) }}
>
    <div
        :class="isDesktop && collapse && !forceShow ? '{{ $tooltipClass . ' absolute ' }}' : ''"
        class="flex items-center justify-center flex-shrink-0 size-8 whitespace-nowrap text-sm z-100"
        data-tip="{{ $label }}"
    >
        @if($icon)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                {!! $icon !!}
            </svg>
        @else
            <div></div>
        @endif
    </div>
    
    <span 
        class="whitespace-nowrap text-sm"
        x-cloak 
        x-show="!isDesktop || !collapse || forceShow"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    >
        {{ $label }}
    </span>
</a>