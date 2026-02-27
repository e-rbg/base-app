<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Admin Dashboard')] class extends Component
{
    //
};
?>

<x-main-container 
    title="Dashboard" 
    :breadcrumbs="[
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        
    ]"
    wire:model.live.debounce.300ms="search"
>
    <div class="grid grid-cols-1 gap-6">
        <!-- MAIN CONTENT HERE -->
        <div class="p-6 max-w-400 mx-auto space-y-10">
            <header class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black tracking-tight text-base-content">
                        System <span class="text-primary">Overview</span>
                    </h2>
                    <p class="text-base-content/60 font-medium">Reporting period: Oct 12 - Oct 19, 2025</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-button icon="cloud-arrow-down" secondary outline label="Export PDF" />
                    <x-button icon="plus" primary label="Create Invoice" class="shadow-lg shadow-primary/20" />
                </div>
            </header>

            <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $stats = [
                        ['label' => 'Total Users', 'value' => '12,842', 'color' => 'primary', 'trend' => '+14%'],
                        ['label' => 'Active Subscriptions', 'value' => '4,210', 'color' => 'secondary', 'trend' => '+2%'],
                        ['label' => 'Avg. Session', 'value' => '12m 4s', 'color' => 'accent', 'trend' => '-5%'],
                        ['label' => 'Pending Support', 'value' => '24', 'color' => 'error', 'trend' => 'Urgent'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="card bg-base-100 shadow-sm border border-base-200 transition-all hover:border-{{ $stat['color'] }}/50">
                    <div class="card-body p-5">
                        <span class="text-xs font-bold opacity-50 uppercase tracking-widest">{{ $stat['label'] }}</span>
                        <div class="flex items-center justify-between mt-2">
                            <h3 class="text-2xl font-black">{{ $stat['value'] }}</h3>
                            <div class="badge badge-{{ $stat['color'] }} badge-outline text-[10px] font-bold">{{ $stat['trend'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </section>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden h-80">
                    <div class="card-body">
                        <h3 class="text-lg font-bold">User Acquisition</h3>
                        <div class="flex-1 mt-4 bg-base-200/50 rounded-xl border-2 border-dashed border-base-300 flex items-center justify-center">
                            <p class="text-xs opacity-40 font-mono">[ Chart.js / Recharts Component ]</p>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden h-80">
                    <div class="card-body">
                        <h3 class="text-lg font-bold">Revenue Streams</h3>
                        <div class="flex-1 mt-4 bg-base-200/50 rounded-xl border-2 border-dashed border-base-300 flex items-center justify-center">
                            <p class="text-xs opacity-40 font-mono">[ Pie Chart Component ]</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black">Transaction History</h3>
                    <div class="join shadow-sm border border-base-200">
                        <button class="btn btn-sm join-item">All</button>
                        <button class="btn btn-sm join-item bg-base-100">Success</button>
                        <button class="btn btn-sm join-item bg-base-100">Pending</button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm">
                    <table class="table table-lg">
                        <thead class="bg-base-200/50">
                            <tr>
                                <th class="text-xs uppercase opacity-50">Reference</th>
                                <th class="text-xs uppercase opacity-50">Customer</th>
                                <th class="text-xs uppercase opacity-50">Status</th>
                                <th class="text-xs uppercase opacity-50">Date</th>
                                <th class="text-right text-xs uppercase opacity-50">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200">
                            @foreach(range(1, 8) as $i)
                            <tr class="hover:bg-base-200/20 transition-colors">
                                <td class="font-mono text-xs opacity-60">#INV-{{ 4000 + $i }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar avatar-placeholder">
                                            <div class="bg-neutral text-neutral-content rounded-full w-8"><span>{{ $i }}</span></div>
                                        </div>
                                        <span class="font-bold text-sm">Customer User {{ $i }}</span>
                                    </div>
                                </td>
                                <td><div class="badge badge-success badge-sm">Successful</div></td>
                                <td class="text-sm opacity-60">Oct 1{{ $i }}, 2025</td>
                                <td class="text-right font-black">$ {{ number_format($i * 125, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
                <div class="lg:col-span-2 card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-lg font-bold mb-4">Upcoming Deployments</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-base-200/50 rounded-xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-primary/20 text-primary flex items-center justify-center font-bold">V4</div>
                                    <div>
                                        <p class="text-sm font-bold">Tailwind v4.0 Patch</p>
                                        <p class="text-xs opacity-50">Scheduled for 14:00 UTC</p>
                                    </div>
                                </div>
                                <x-button xs label="Manage" outline />
                            </div>
                            </div>
                    </div>
                </div>

                <div class="card bg-primary text-primary-content shadow-xl shadow-primary/20">
                    <div class="card-body">
                        <h3 class="text-xl font-bold">Upgrade to Pro</h3>
                        <p class="text-sm opacity-80 leading-relaxed">Unlock advanced analytics, custom reports, and unlimited team members.</p>
                        <div class="card-actions mt-4">
                            <button class="btn btn-block bg-white text-primary border-none hover:bg-base-200">Go Premium</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MAIN CONTENT HERE -->
    </div>
</x-main-container>

{{-- <script>
    document.addEventListener('keydown', (e) => {
        // We check for both 'k' and 'K' to be safe
        if ((e.metaKey || e.ctrlKey) && (e.key === 'k' || e.key === 'K')) {

            // This is the crucial line that stops Chrome from taking over
            e.preventDefault();
            e.stopImmediatePropagation();

            // Target your specific search input
            const searchInput = document.querySelector('input[type="search"]');

            if (searchInput) {
                searchInput.focus();
                // Optional: Select the text so the user can just start typing over it
                searchInput.select();
            }
        }
    }, true); // The 'true' here uses the "Capture" phase to catch the key before the browser
</script> --}}


