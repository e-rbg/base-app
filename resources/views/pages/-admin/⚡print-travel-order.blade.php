<?php

use App\Models\TravelOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.auth')] // Using a dedicated print layout is cleaner
    #[Title('Travel Order Print')]
    class extends Component {
    
    public TravelOrder $order;

    public function mount($id)
    {
        // Eager load relationships here if you have them (e.g., $this->order->load('user'))
        $this->order = TravelOrder::findOrFail($id);
    }
}; ?>

<div class="flex flex-col items-center py-10 print:py-0">
    {{-- Screen-Only Controls: Hidden when printing --}}
    <div class="mb-6 flex w-full max-w-[8.5in] items-center justify-between rounded-xl bg-white p-4 shadow-sm print:hidden">
        <x-button flat gray icon="arrow-left" label="Back" :href="route('admin.travel-orders.index')" />
        <div class="flex gap-2">
            <x-button black icon="printer" label="Print Travel Order" onclick="window.print()" />
        </div>
    </div>

    {{-- The Document (Standard Letter Size) --}}
    <div class="w-full max-w-[8.5in] border border-gray-200 bg-white p-[0.75in] shadow-2xl print:m-0 print:border-none print:p-[0.5in] print:shadow-none">
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b-2 border-black pb-4">
            <img src="{{ asset('images/dar-logo.png') }}" class="h-16 w-auto">
            <div class="text-center">
                <p class="m-0 text-[10px] uppercase tracking-widest">Republic of the Philippines</p>
                <h1 class="m-0 font-serif text-xl font-bold uppercase">Department of Agrarian Reform</h1>
                <p class="m-0 text-sm font-semibold">Provincial Agrarian Reform Office</p>
                <p class="m-0 text-xs">Davao de Oro</p>
            </div>
            <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" class="h-16 w-auto">
        </div>

        {{-- Document Body --}}
        <div class="my-10 text-center">
            <h2 class="font-serif text-3xl font-black">TRAVEL ORDER</h2>
            <p class="mt-1 font-mono">No. <span class="underline">{{ $order->travel_order_no }}</span></p>
        </div>

        <div class="grid grid-cols-2 gap-y-8 text-sm">
            <div class="col-span-1">
                <label class="text-[9px] font-black uppercase text-gray-400">Personnel Name</label>
                <p class="text-lg font-bold uppercase">{{ $order->name }}</p>
            </div>
            <div class="col-span-1 text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Date Issued</label>
                <p class="text-lg">{{ $order->travel_date->format('F d, Y') }}</p>
            </div>

            <div class="col-span-1">
                <label class="text-[9px] font-black uppercase text-gray-400">Position</label>
                <p class="text-md">{{ $order->position }}</p>
            </div>
            <div class="col-span-1 text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Station</label>
                <p class="text-md">{{ $order->station }}</p>
            </div>

            <div class="col-span-2 mt-4">
                <label class="text-[9px] font-black uppercase text-gray-400">Purpose</label>
                <ul class="mt-2 space-y-2 italic">
                    @foreach($order->purpose_of_trip as $purpose)
                        <li class="flex items-start gap-2">
                            <span class="font-bold">•</span> {{ $purpose }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Signatures --}}
        <div class="mt-24 grid grid-cols-2 gap-10 text-center">
            <div>
                <p class="mb-14 text-left text-[9px] font-bold uppercase text-gray-400">Approved By:</p>
                <p class="border-b-2 border-black font-bold uppercase">{{ $order->approved_by_name }}</p>
                <p class="text-xs">{{ $order->approved_by_position }}</p>
            </div>
            <div>
                <p class="mb-14 text-left text-[9px] font-bold uppercase text-gray-400">Verified By:</p>
                <p class="border-b-2 border-black font-bold uppercase">{{ $order->fund_custodian }}</p>
                <p class="text-xs">Budget Officer</p>
            </div>
        </div>
    </div>
</div>