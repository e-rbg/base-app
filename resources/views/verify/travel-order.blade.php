<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Travel Order {{ $travelOrder->travel_order_no }} | DAR Davao de Oro</title>
    @vite(['resources/css/app.css'])
    <style>
        html, body { margin: 0; padding: 0; min-height: 100vh; }
        body { background: #f3f4f6; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="w-full max-w-lg mx-auto p-4 sm:p-6">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-6 text-center">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <img src="{{ asset('images/dar-logo.png') }}" class="w-12 h-12" alt="DAR">
                    <div class="text-left text-white">
                        <p class="text-[10px] uppercase tracking-widest opacity-80">Republic of the Philippines</p>
                        <h1 class="text-sm font-bold uppercase leading-tight">Department of Agrarian Reform</h1>
                        <p class="text-xs font-semibold opacity-80">Provincial Agrarian Reform Office — Davao de Oro</p>
                    </div>
                    <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" class="w-10 h-10" alt="BP">
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 text-white text-xs font-bold uppercase tracking-wider">
                    <x-icon name="check-badge" class="w-4 h-4" />
                    Document Verification
                </div>
            </div>

            {{-- Status --}}
            <div class="px-6 pt-6">
                @php
                    $status = $travelOrder->status;
                    $isApproved = $status === 'approved';
                    $isPending = $status === 'pending' || $status === 'for_approval';
                    $isDisapproved = $status === 'disapproved';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black">Travel Order</h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $isApproved ? 'bg-emerald-100 text-emerald-700' : ($isDisapproved ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        <span class="w-2 h-2 rounded-full {{ $isApproved ? 'bg-emerald-500' : ($isDisapproved ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                        {{ $status }}
                    </span>
                </div>

                {{-- Details --}}
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="text-gray-400 font-medium">Order No.</span>
                        <span class="font-bold font-mono">{{ $travelOrder->travel_order_no }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="text-gray-400 font-medium">Travel Date</span>
                        <span class="font-semibold">{{ $travelOrder->travel_date?->format('F d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="text-gray-400 font-medium">Personnel Name</span>
                        <span class="font-semibold">{{ $travelOrder->name }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="text-gray-400 font-medium">Position</span>
                        <span class="font-semibold">{{ $travelOrder->position }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="text-gray-400 font-medium">Station</span>
                        <span class="font-semibold text-right">{{ $travelOrder->station }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="text-gray-400 font-medium">Destination</span>
                        <span class="font-semibold text-right">{{ $travelOrder->formattedDestination() }}</span>
                    </div>
                    <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                        <span class="text-gray-400 font-medium">Travel Period</span>
                        <span class="font-semibold text-right">
                            {{ $travelOrder->departure_date?->format('M d, Y') ?? '—' }}
                            @if($travelOrder->return_date)
                                &rarr; {{ $travelOrder->return_date->format('M d, Y') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Signatories --}}
            <div class="px-6 pt-4 pb-2">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Signatories</h3>
                <div class="space-y-3">
                    @if($travelOrder->approved_by_name)
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ $isApproved ? 'bg-emerald-50 border border-emerald-200' : 'bg-gray-50 border border-gray-200' }}">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $isApproved ? 'bg-emerald-200 text-emerald-700' : 'bg-gray-200 text-gray-500' }} flex-shrink-0">
                                <x-icon name="check" class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-sm font-bold">{{ $travelOrder->approved_by_name }}</p>
                                <p class="text-xs text-gray-500">{{ $travelOrder->approved_by_position ?? 'Approving Authority' }}</p>
                                @if($travelOrder->esignature_hash)
                                    <p class="text-[10px] font-mono text-gray-400 mt-1 break-all">Hash: {{ $travelOrder->esignature_hash }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($travelOrder->recommending_approval)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-500 flex-shrink-0">
                                <x-icon name="user" class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-sm font-bold">{{ $travelOrder->recommending_approval }}</p>
                                <p class="text-xs text-gray-500">{{ $travelOrder->recommending_position ?? 'Recommending Authority' }}</p>
                                @if($travelOrder->esignature_recommender_hash)
                                    <p class="text-[10px] font-mono text-gray-400 mt-1 break-all">Hash: {{ $travelOrder->esignature_recommender_hash }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Authenticity badge --}}
            <div class="px-6 pb-6 pt-2">
                @if($isApproved)
                    <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-500/5 border border-emerald-500/20 text-emerald-700">
                        <x-icon name="shield-check" class="w-5 h-5 flex-shrink-0" />
                        <div>
                            <p class="text-sm font-bold">Document is authentic</p>
                            <p class="text-xs opacity-70">This travel order was digitally signed and verified.</p>
                        </div>
                    </div>
                @elseif($isDisapproved)
                    <div class="flex items-center gap-2 p-3 rounded-xl bg-red-500/5 border border-red-500/20 text-red-700">
                        <x-icon name="x-circle" class="w-5 h-5 flex-shrink-0" />
                        <div>
                            <p class="text-sm font-bold">Document is not approved</p>
                            <p class="text-xs opacity-70">This travel order has been marked as disapproved.</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 p-3 rounded-xl bg-amber-500/5 border border-amber-500/20 text-amber-700">
                        <x-icon name="clock" class="w-5 h-5 flex-shrink-0" />
                        <div>
                            <p class="text-sm font-bold">Pending approval</p>
                            <p class="text-xs opacity-70">This travel order has not yet been processed.</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-400">
                    Verified via DAR Davao de Oro Document Verification System &bull;
                    {{ now()->format('F d, Y h:i A') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>