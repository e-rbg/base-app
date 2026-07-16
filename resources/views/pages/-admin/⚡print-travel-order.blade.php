<?php

use App\Models\TravelOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.print')]
    #[Title('Travel Order Print')]
    class extends Component {

    public TravelOrder $order;

    public function mount($id)
    {
        $this->order = TravelOrder::findOrFail($id);
    }
}; ?>

<div class="flex flex-col items-center py-10 print:py-0">
    {{-- Screen-Only Controls --}}
    <div class="mb-6 flex w-full max-w-[8.5in] items-center justify-between rounded-xl bg-white p-4 shadow-sm print:hidden">
        <x-button flat gray icon="arrow-left" label="Back" :href="route('admin.travel-orders')" />
        <div class="flex gap-2">
            <x-button black icon="printer" label="Print Travel Order" onclick="window.print()" />
            <x-button secondary icon="folder-arrow-down" label="Download PDF" href="{{ route('travel-order.pdf', $order->id) }}?download=1" />
        </div>
    </div>

    {{-- The Document --}}
    <div class="w-full max-w-[8.5in] border border-gray-200 bg-white p-[0.5in] shadow-2xl print:m-0 print:border-none print:p-0 print:shadow-none">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b-2 border-black pb-4">
            @if(file_exists(public_path('images/dar-logo.png')))
                <img src="{{ asset('images/dar-logo.png') }}" class="h-16 w-auto">
            @else
                <div class="flex h-16 w-16 items-center justify-center border text-[8px]">DAR LOGO</div>
            @endif
            <div class="text-center">
                <p class="m-0 text-[10px] uppercase tracking-widest">Republic of the Philippines</p>
                <h1 class="m-0 font-serif text-xl font-bold uppercase">Department of Agrarian Reform</h1>
                <p class="m-0 text-sm font-semibold">Provincial Agrarian Reform Office</p>
                <p class="m-0 text-xs">Davao de Oro</p>
            </div>
            @if(file_exists(public_path('images/bagong-pilipinas-logo.png')))
                <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" class="h-16 w-auto">
            @else
                <div class="flex h-16 w-16 items-center justify-center border text-[8px]">BP LOGO</div>
            @endif
        </div>

        {{-- Title --}}
        <div class="my-8 text-center">
            <h2 class="font-serif text-3xl font-black">TRAVEL ORDER</h2>
            <p class="mt-1 font-mono">No. <span class="underline">{{ $order->travel_order_no }}</span></p>
        </div>

        {{-- Personnel Info --}}
        <div class="grid grid-cols-2 gap-y-3 text-sm">
            <div>
                <label class="text-[9px] font-black uppercase text-gray-400">Personnel Name</label>
                <p class="text-lg font-bold uppercase">{{ $order->name }}</p>
            </div>
            <div class="text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Date Issued</label>
                <p class="">{{ $order->travel_date->format('F d, Y') }}</p>
            </div>

            <div class="">
                <label class="text-[9px] font-black uppercase text-gray-400">Position</label>
                <p class="">{{ $order->position }}</p>
            </div>
            <div class=" text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Station</label>
                <p class="">{{ $order->station }}</p>
            </div>

            <div class="">
                <label class="text-[9px] font-black uppercase text-gray-400">Travel Type</label>
                <p class="">{{ str_replace('_', ' ', ucfirst($order->travel_type)) }}</p>
            </div>
            <div class="text-right">
                <label class="text-[9px] font-black uppercase text-gray-400">Travel Period</label>
                <p class="">{{ $order->departure_date->format('M d') }} – {{ $order->return_date->format('M d, Y') }}</p>
            </div>

            @if($order->destination)
            <div class="col-span-2">
                <label class="text-[9px] font-black uppercase text-gray-400">Destination</label>
                <p class="">{{ $order->formattedDestination() }}</p>
            </div>
            @endif

            @if($order->report_to)
            <div class="col-span-2">
                <label class="text-[9px] font-black uppercase text-gray-400">Report To</label>
                <p class="">{{ $order->report_to }}</p>
            </div>
            @endif
        </div>

        {{-- Purpose --}}
        <div class="">
            <label class="text-[9px] font-black uppercase text-gray-400">Purpose of Trip</label>
            <div class="mt-2 border border-gray-200 bg-gray-50 p-4">
                <ul class="list-disc space-y-2 pl-6">
                    @foreach($order->purpose_of_trip as $purpose)
                        <li class="italic">{{ $purpose }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Logistics --}}
        @php
            $vt = is_array($order->vehicle_type) ? $order->vehicle_type : [$order->vehicle_type];
            $isGovt = in_array('Government Vehicle', $vt);
            $others = array_filter($vt, fn($v) => $v !== 'Government Vehicle');
            $acc = is_array($order->accommodation_type) ? $order->accommodation_type : [$order->accommodation_type];
        @endphp
        <div class="mt-6 grid grid-cols-3 gap-2 border-t border-gray-100 pt-4 text-xs">
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold">{{ $order->transportation_means === 'Land' ? 'X' : '' }}</div>
                <span class="uppercase">Land Travel</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold">{{ $isGovt ? 'X' : '' }}</div>
                <span class="uppercase">Govt Vehicle</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold">{{ count($others) ? 'X' : '' }}</div>
                <span class="uppercase">Others: <span class="underline ml-1">{{ count($others) ? implode(', ', $others) : '________' }}</span></span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold">{{ in_array('Live-out', $acc) ? 'X' : '' }}</div>
                <span class="uppercase">Live-out</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex h-4 w-4 items-center justify-center border border-black font-bold">{{ in_array('Live-in', $acc) ? 'X' : '' }}</div>
                <span class="uppercase">Live-in</span>
            </div>
        </div>

        {{-- Signatures --}}
        <div class="mt-20 grid grid-cols-2 gap-10 text-center">
            @if(!empty($order->recommending_approval) && $order->recommending_approval !== 'N/A')
            <div>
                <p class="mb-14 text-left text-[9px] font-bold uppercase text-gray-400">Recommending Approval:</p>
                <p class="border-b-2 border-black font-bold uppercase">{{ $order->recommending_approval }}</p>
                <p class="text-xs italic">{{ $order->recommending_position }}</p>
                @if($order->recommending_position)
                    <p class="text-xs italic mt-1">{{ $order->recommending_position }}</p>
                @endif
            </div>
            @endif
            <div>
                <p class="mb-14 text-left text-[9px] font-bold uppercase text-gray-400">Approved By:</p>
                <p class="border-b-2 border-black font-bold uppercase">{{ $order->approved_by_name }}</p>
                <p class="text-xs italic">{{ $order->approved_by_position }}</p>
            </div>
        </div>
    </div>
</div>
