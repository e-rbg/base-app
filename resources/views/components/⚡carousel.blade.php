<?php

use Livewire\Component;

new class extends Component {
    public array $slides = [
        ['id' => 1, 'image' => 'https://picsum.photos/id/10/1200/400', 'title' => 'First Adventure', 'desc' => 'Explore the mountains'],
        ['id' => 2, 'image' => 'https://picsum.photos/id/20/1200/400', 'title' => 'Deep Oceans', 'desc' => 'Discover the blue'],
        ['id' => 3, 'image' => 'https://picsum.photos/id/24/1200/400', 'title' => 'Golden Deserts', 'desc' => 'Feel the heat'],
    ];
}; ?>

<div x-data="{
        activeSlide: 0,
        slidesCount: {{ count($slides) }},
        next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount }
    }"
    x-init="setInterval(() => next(), 5000)"
    class="relative w-full max-w-5xl mx-auto overflow-hidden rounded-2xl shadow-xl group"
>
    <div class="relative h-64 sm:h-80 md:h-96">
        @foreach($slides as $index => $slide)
            <div x-show="activeSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0"
            >
                <img src="{{ $slide['image'] }}" class="object-cover w-full h-full" alt="">
                <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent flex flex-col justify-end p-8 text-white">
                    <h2 class="text-2xl font-bold">{{ $slide['title'] }}</h2>
                    <p class="text-sm opacity-80">{{ $slide['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-md p-2 rounded-full text-white transition-all opacity-0 group-hover:opacity-100">
        <x-icon name="chevron-left" class="w-6 h-6" />
    </button>

    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-md p-2 rounded-full text-white transition-all opacity-0 group-hover:opacity-100">
        <x-icon name="chevron-right" class="w-6 h-6" />
    </button>

    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
        @foreach($slides as $index => $slide)
            <button @click="activeSlide = {{ $index }}"
                    class="h-2 rounded-full transition-all duration-300"
                    :class="activeSlide === {{ $index }} ? 'w-8 bg-primary' : 'w-2 bg-white/50 hover:bg-white'">
            </button>
        @endforeach
    </div>
</div>
