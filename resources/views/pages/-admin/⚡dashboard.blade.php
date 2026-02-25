<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Admin Dashboard')] class extends Component
{
    //
};
?>

<div class="flex flex-col h-screen w-full overflow-hidden relative">
    <div class="flex-shrink-0 py-3 px-6 sticky top-0 z-10" >
        <livewire:breadcrumbs :items="[
            ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
            ['label' => 'Testing']
        ]"/>
        <div class="flex items-center justify-between border-b border-neutral-300 dark:border-neutral-700 py-3">
            <h1 class="text-xl font-bold w-full">Dashboard</h1>
            <label x-show="isDesktop" class="input w-full">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g
                    stroke-linejoin="round"
                    stroke-linecap="round"
                    stroke-width="2.5"
                    fill="none"
                    stroke="currentColor"
                    >
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                    </g>
                </svg>
                <input type="search" class="grow font-roboto" placeholder="Search" />
                <kbd class="kbd kbd-sm">⌘</kbd>
                <kbd class="kbd kbd-sm">K</kbd>
            </label>
        </div>
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto p-5">
        <div class="max-w-full border border-primary p-6">
            <p>Lorem Ipsum (Long Text)...</p>
        </div>
    </div>

</div>
