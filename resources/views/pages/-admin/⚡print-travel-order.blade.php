<?php

use App\Models\TravelOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;

new #[Layout('layouts.print')]
    #[Title('Travel Order Print')]
    class extends Component {

    use WireUiActions;

    public TravelOrder $order;
    public string $verifyInput = '';
    public ?bool $verificationResult = null;

    public function mount($id)
    {
        $this->order = TravelOrder::findOrFail($id);
    }

    public function verifySignature(): void
    {
        $this->verificationResult = ($this->verifyInput === $this->order->esignature_hash);
    }

    public function approveOrder()
    {
        $user = auth()->user();
        $isApprover = str_contains($this->order->approved_by_name, $user->full_name);

        if (!$isApprover) {
            return $this->notification()->error('Unauthorized', 'You are not a designated signatory for this Travel Order.');
        }

        $this->dialog()->confirm([
            'title'       => 'Approve Travel Order?',
            'description' => 'This will mark travel order no. ' . $this->order->travel_order_no . ' as approved. The document will be locked.',
            'icon'        => 'check',
            'accept'      => [
                'label'  => 'Approve',
                'color'  => 'positive',
                'method' => 'performApprove',
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performApprove()
    {
        $user = auth()->user();
        $signature = $user->activeSignature();

        if (!$signature) {
            return $this->notification()->error('No Signature', 'Please create and activate a digital signature first.');
        }

        $this->order->update([
            'status' => 'approved',
            'approved_at' => now(),
            'esignature_hash' => $signature->esignature_hash,
        ]);

        $this->order->refresh();
        $this->notification()->success('Travel Order Approved', 'The document is now locked and ready for printing.');
    }

    public function disapproveOrder()
    {
        $user = auth()->user();
        $isApprover = str_contains($this->order->approved_by_name, $user->full_name);

        if (!$isApprover) {
            return $this->notification()->error('Unauthorized', 'You are not a designated signatory for this Travel Order.');
        }

        $this->dialog()->confirm([
            'title'       => 'Disapprove Travel Order?',
            'description' => 'This will mark travel order no. ' . $this->order->travel_order_no . ' as disapproved.',
            'icon'        => 'x-mark',
            'accept'      => [
                'label'  => 'Disapprove',
                'color'  => 'negative',
                'method' => 'performDisapprove',
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performDisapprove()
    {
        $this->order->update([
            'status' => 'disapproved',
        ]);

        $this->order->refresh();
        $this->notification()->warning('Travel Order Disapproved', 'The document has been marked as disapproved.');
    }
}; ?>

<div class="flex flex-col items-center py-10 print:py-0">
    {{-- Screen-Only Controls --}}
    <div class="mb-6 flex w-full max-w-[8.5in] items-center justify-between rounded-xl bg-white p-4 shadow-sm print:hidden">
        <div class="flex items-center gap-3">
            <x-button flat gray icon="arrow-left" label="Back" :href="route('admin.travel-orders')" />
            @if($order->status === 'pending')
                <x-badge flat warning label="Pending" icon="clock" />
            @elseif($order->status === 'approved')
                <x-badge flat success label="Approved" icon="check" />
            @elseif($order->status === 'disapproved')
                <x-badge flat error label="Disapproved" icon="x-mark" />
            @endif
        </div>
        <div class="flex gap-2">
            @php
                $isApprover = str_contains($order->approved_by_name, auth()->user()->full_name ?? '');
            @endphp
            @if($order->status === 'pending' && $isApprover)
                <x-button positive icon="check" label="Approve" wire:click="approveOrder" />
                <x-button negative icon="x-mark" label="Disapprove" wire:click="disapproveOrder" />
            @endif
            <x-button black icon="printer" label="Print Travel Order" onclick="window.print()" />
            <x-button secondary icon="folder-arrow-down" label="Download PDF" href="{{ route('travel-order.pdf', $order->id) }}?download=1" />
        </div>
    </div>

    {{-- The Document --}}
    <div class="w-full max-w-[8.5in] border border-gray-200 bg-white p-[0.5in] shadow-2xl print:m-0 print:border-none print:p-0 print:shadow-none">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b-2 border-black pb-4">
            @if(file_exists(storage_path('app/public/dar-logo.webp')))
                <img src="{{ asset('storage/dar-logo.webp') }}" class="h-16 w-auto">
            @else
                <div class="flex h-16 w-16 items-center justify-center border text-[8px]">DAR LOGO</div>
            @endif
            <div class="text-center">
                <p class="m-0 text-[10px] uppercase tracking-widest">Republic of the Philippines</p>
                <h1 class="m-0 font-serif text-xl font-bold uppercase">Department of Agrarian Reform</h1>
                <p class="m-0 text-sm font-semibold">Provincial Agrarian Reform Office</p>
                <p class="m-0 text-xs">Davao de Oro</p>
            </div>
            @if(file_exists(storage_path('app/public/bagong-pilipinas-logo.png')))
                <img src="{{ asset('storage/bagong-pilipinas-logo.png') }}" class="h-16 w-auto">
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
                <label class="text-[9px] font-black uppercase text-gray-400">Travel Order Date</label>
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
            @if($order->travel_type !== 'intra_municipal' && !empty($order->recommending_approval) && $order->recommending_approval !== 'N/A')
            <div>
                <p class="mb-5 text-left text-[9px] font-bold uppercase text-gray-400">Recommending Approval:</p>
                <p class="border-b-2 border-black font-bold uppercase">{{ $order->recommending_approval }}</p>
                @if($order->recommending_position)
                    <p class="text-xs italic">{{ $order->recommending_position }}</p>
                @endif
            </div>
            @endif
            <div>
                <p class="mb-5 text-left text-[9px] font-bold uppercase text-gray-400">Approved By:</p>
                @if($order->status === 'approved' && $order->esignature_hash)
                    <div class="mb-2 w-1/4 flex flex-col justify-center items-center">
                        @if($qrCode = \App\Models\TravelOrder::generateQrFromHash($order->esignature_hash))
                            <img src="{{ $qrCode }}" alt="E-signature QR code" width="100" height="100" />
                        @endif
                        <p class="font-mono text-[8px] tracking-tight leading-none mt-1">{{ $order->esignature_hash }}</p>
                    </div>
                @endif
                <p class="border-b-2 border-black font-bold uppercase">{{ $order->approved_by_name }}</p>
                <p class="text-xs italic">{{ $order->approved_by_position }}</p>
            </div>
        </div>
    </div>

    {{-- Signature Verification (screen only) --}}
    @if($order->esignature_hash)
    <div class="mt-6 w-full max-w-[8.5in] rounded-xl bg-white p-4 shadow-sm print:hidden">
        <h3 class="mb-3 text-sm font-bold">Verify Signature</h3>
        <div class="flex items-end gap-3">
            <x-input wire:model="verifyInput" placeholder="Enter the signature text from the document"
                icon="finger-print" shadow="none" class="flex-1" />
            <x-button primary label="Verify" wire:click="verifySignature" />
        </div>
        @if($verificationResult !== null)
            @if($verificationResult)
                <div class="mt-3 flex items-center gap-2 text-sm text-green-600">
                    <x-icon name="check-circle" class="h-5 w-5" /> Signature is valid — matches the approved record.
                </div>
            @else
                <div class="mt-3 flex items-center gap-2 text-sm text-red-600">
                    <x-icon name="x-circle" class="h-5 w-5" /> Signature does not match — possible forgery.
                </div>
            @endif
        @endif
    </div>
    @endif
</div>
