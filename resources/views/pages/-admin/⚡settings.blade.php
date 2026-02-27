<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;


new #[Layout('layouts.app')] #[Title('Admin Settings')] class extends Component
{
    
    use \WireUi\Traits\Actions;

    public function updateTheme(string $theme)
    {
        auth()->user()->profile->update([
            'preferences->theme' => $theme,
        ]);

        $this->dispatch('theme-updated', theme: $theme);

        // Optional: Let the user know it saved
        $this->notification()->success(
            title: 'Appearance Updated',
            description: "You are now using {$theme} mode."
        );
    }
};
?>

<x-main-container 
    title="Settings" 
    :breadcrumbs="[
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => 'Settings']
    ]"
    wire:model.live.debounce.300ms="search"
>
    <div class="grid grid-cols-1 gap-6">
        <div class="py-2">
            <div class="card bg-base-200 border border-base-300 shadow-sm overflow-hidden sm:w-1/4 w-full py-2 px-3">
                <div class="p-4 space-y-4">
                    {{-- Heading Section --}}
                    <div>
                        <h3 class="text-sm font-bold text-base-content tracking-tight">Theme Selector</h3>
                        <p class="text-[11px] text-base-content/50 font-medium leading-none mt-1">Switch to your desired theme.</p>
                    </div>

                    {{-- Interactive Toggle Button --}}
                    <button 
                        {{-- We call the backend, which then triggers the frontend via the event --}}
                        @click="$wire.updateTheme(theme === 'light' ? 'dark' : 'light')" 
                        class="group relative flex items-center justify-between w-full p-2 rounded-lg bg-base-200/50 hover:bg-base-200 transition-all duration-200 active:scale-[0.97] border border-transparent hover:border-base-300"
                    >
                        <div class="flex items-center gap-3">
                            {{-- Animated Icon --}}
                            <div class="flex items-center justify-center size-8 rounded-md bg-base-100 shadow-sm border border-base-300/30 text-primary">
                                <x-icon 
                                    x-show="theme === 'dark'" 
                                    name="sun" 
                                    class="size-5 animate-in zoom-in duration-300" 
                                    x-cloak 
                                />
                                <x-icon 
                                    x-show="theme === 'light'" 
                                    name="moon" 
                                    class="size-5 animate-in zoom-in duration-300" 
                                    x-cloak 
                                />
                            </div>
                            
                            <span class="text-xs font-semibold text-base-content/80 text-secondary" x-text="theme === 'light' ? 'Dark Mode' : 'Light Mode'"></span>
                        </div>

                        {{-- Status Indicator --}}
                        <div class="flex items-center">
                            <div class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse mr-2"></div>
                            <span class="text-[10px] font-bold uppercase tracking-tighter opacity-40" x-text="theme"></span>
                        </div>
                    </button>
                    <x-button  @click="localStorage.removeItem('theme'); location.reload();">
                        Use System Settings
                    </x-button>
                </div>
            </div>
            
        </div>
    </div>
</x-main-container>