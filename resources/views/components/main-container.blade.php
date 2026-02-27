@props([
    'title' => 'Dashboard',
    'breadcrumbs' => []
])

<div class="flex flex-col h-screen w-full bg-base-100 overflow-hidden">
    <div class="shrink-0 z-20 bg-base-100/80 backdrop-blur-md border-b border-base-200">
        <div class="px-6 py-3 shadow-sm">
            {{-- Breadcrumbs Component --}}
            @if(!empty($breadcrumbs))
                <livewire:breadcrumbs :items="$breadcrumbs" />
            @endif

            <div class="flex items-center justify-between gap-4 mt-1">
                <h1 class="text-2xl font-bold text-base-content tracking-tight">
                    {{ $title }}
                </h1>
                @if ($attributes->has('wire:model'))
                <div class="flex-1 max-w-md" x-show="isDesktop">
                    <label class="input input-bordered flex items-center gap-2 h-10 bg-base-200/50 border-none focus-within:ring-1 ring-primary">
                        <x-icon name="magnifying-glass" class="w-4 h-4 opacity-70" />
                        <input
                            type="search"
                            x-on:keydown.window.cmd.k.prevent="$el.focus()"
                            x-on:keydown.window.ctrl.k.prevent="$el.focus()"
                            class="grow text-sm font-roboto"
                            placeholder="Search anything..."
                            {{ $attributes->whereStartsWith('wire:model') }} 
                        />
                        <div class="flex gap-1 items-center opacity-50">
                            <kbd class="kbd kbd-sm text-[10px]">⌘</kbd>
                            <kbd class="kbd kbd-sm text-[10px]">K</kbd>
                        </div>
                    </label>
                </div>
                @endif
            </div>
        </div>
    </div>

    <main class="flex-1 min-h-0 overflow-y-auto custom-scrollbar">
        <div class="p-6 max-w-[1600px] mx-auto space-y-10">
            {{ $slot }}
        </div>
    </main>
</div>