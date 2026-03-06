<x-layouts::auth title="Printing Travel Order- {{ $order->travel_order_no }}">
    <div class="py-12 bg-gray-100 min-h-screen print:bg-white print:py-0">
        <div class="max-w-4xl mx-auto">
            
            {{-- Action Bar: Hidden during print --}}
            <div class="flex justify-between items-center mb-6 px-4 print:hidden">
                <x-button flat icon="arrow-left" label="Back to List" href="{{ route('travel-orders.pdf') }}" />
                <x-button primary icon="printer" label="Print Travel Order" onclick="window.print()" />
            </div>

            {{-- The "Paper" Sheet --}}
            <div class="bg-white shadow-xl p-12 rounded-lg border border-gray-200 print:shadow-none print:border-none print:p-0">
                
                {{-- Header Section --}}
                <div class="flex justify-between items-center border-b-2 border-gray-900 pb-6 mb-8">
                    <img src="{{ asset('images/dar-logo.png') }}" class="w-20">
                    <div class="text-center">
                        <p class="text-sm">Republic of the Philippines</p>
                        <h1 class="font-bold text-xl uppercase">Department of Agrarian Reform</h1>
                        <p class="text-md font-semibold">Provincial Agrarian Reform Office</p>
                        <p class="text-sm">Davao de Oro</p>
                    </div>
                    <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" class="w-20">
                </div>

                <div class="text-center my-8">
                    <h2 class="text-2xl font-bold">TRAVEL ORDER No. <span class="underline">{{ $order->travel_order_no }}</span></h2>
                </div>

                {{-- Data Grid --}}
                <div class="grid grid-cols-2 gap-y-6 text-sm">
                    <div>
                        <label class="block font-bold text-gray-500 uppercase text-[10px]">Name of Personnel</label>
                        <p class="text-lg font-bold border-b border-gray-300 pb-1">{{ $order->name }}</p>
                    </div>
                    <div class="text-right">
                        <label class="block font-bold text-gray-500 uppercase text-[10px]">Date</label>
                        <p class="text-lg border-b border-gray-300 pb-1">{{ \Carbon\Carbon::parse($order->travel_date)->format('F d, Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block font-bold text-gray-500 uppercase text-[10px]">Position</label>
                        <p class="border-b border-gray-300 pb-1">{{ $order->position }}</p>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-500 uppercase text-[10px]">Official Station</label>
                        <p class="border-b border-gray-300 pb-1">{{ $order->station }}</p>
                    </div>

                    {{-- Purpose Section with Unordered List --}}
                    <div class="col-span-2 mt-4">
                        <label class="block font-bold text-gray-500 uppercase text-[10px] mb-2">Purpose of Trip</label>
                        <div class="border border-gray-200 rounded-md p-4 bg-gray-50 print:bg-white">
                            <ul class="list-disc pl-6 space-y-2">
                                @foreach($order->purpose_of_trip as $purpose)
                                    <li class="text-md italic">{{ $purpose }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Logistics Grid --}}
                    <div class="col-span-2 grid grid-cols-3 gap-4 border-t border-gray-100 pt-6">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-black flex items-center justify-center font-bold">
                                {{ $order->transportation_means === 'Land' ? 'X' : '' }}
                            </div>
                            <span class="text-xs uppercase">Land Travel</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-black flex items-center justify-center font-bold">
                                {{ $order->vehicle_type === 'Government Vehicle' ? 'X' : '' }}
                            </div>
                            <span class="text-xs uppercase">Govt Vehicle</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-black flex items-center justify-center font-bold text-[10px]">
                                {{ $order->vehicle_type !== 'Government Vehicle' ? 'X' : '' }}
                            </div>
                            <span class="text-xs uppercase">Others: <span class="underline ml-1">{{ $order->vehicle_type !== 'Government Vehicle' ? $order->vehicle_type : '________' }}</span></span>
                        </div>
                    </div>
                </div>

                {{-- Signatures --}}
                <div class="grid grid-cols-2 gap-12 mt-16">
                    <div class="text-center">
                        <p class="text-[10px] uppercase text-gray-400 mb-10 text-left">Approved By:</p>
                        <p class="font-bold uppercase underline">{{ $order->approved_by_name }}</p>
                        <p class="text-xs italic">{{ $order->approved_by_position }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] uppercase text-gray-400 mb-10 text-left">Funds Available:</p>
                        <p class="font-bold uppercase underline">{{ $order->fund_custodian }}</p>
                        <p class="text-xs italic">Budget Officer</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts::auth>