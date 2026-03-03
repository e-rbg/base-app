<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public bool $show = false;
    public string $url = '';
    public string $title = '';

    #[On('preview-image')]
    public function open($url, $title = 'Image Preview')
    {
        $this->url = $url;
        $this->title = $title;
        $this->show = true;
    }
}; ?>

<div>
    <template x-teleport="body">
        <div 
            x-show="$wire.show" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
        >
            <div @click.away="$wire.show = false" class="w-full max-w-2xl">
                <x-card :title="$title" rounded="3xl">
                    <div class="flex justify-center bg-base-200/50 rounded-2xl overflow-hidden min-h-[300px]">
                        @if($url)
                            <img src="{{ $url }}" class="max-w-full max-h-[75vh] object-contain" />
                        @endif
                    </div>

                    <x-slot name="footer" class="flex justify-end gap-x-4">
                        <x-button flat label="Close" @click="$wire.show = false" />
                        <x-button primary label="Download" tag="a" :href="$url" download />
                    </x-slot>
                </x-card>
            </div>
        </div>
    </template>
</div>


    {{-- 
    USAGE FOR OTHER IMAGES:
    <img 
        src="{{ $user->avatar_url }}" 
        class="cursor-pointer"
        @click="$dispatch('preview-image', { 
            url: '{{ $user->avatar_url }}', 
            title: '{{ $user->name }}' 
        })"
    >  
    --}}