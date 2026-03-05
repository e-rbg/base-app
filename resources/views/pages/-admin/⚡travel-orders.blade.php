<?php

use App\Models\TravelOrder;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use WireUi\Traits\WireUiActions;

new #[Layout('layouts.app', ['title' => 'Travel Orders'])] class extends Component {
    use WireUiActions;

    public bool $modal = false;
    public ?TravelOrder $editing = null;

    // Form Fields
    public $travel_order_no, $travel_date, $name, $position, $station;
    public $transportation_means = 'Land';
    public $vehicle_selection = 'government vehicle';
    public $custom_vehicle = '';
    public $destination, $departure_date, $return_date, $report_to;
    public $purpose_of_trip = [];
    public $accommodation_type = 'live-out';
    public $approved_by_name;
    public $approved_by_position;
    public $fund_custodian ='Maria Siezamie B. Agoilo';
    public $travel_type = 'intra_municipal';
    public $recommending_approval = '';
    public $recommending_position = '';
    public bool $readOnly = false;

    #[Computed]
    public function travelOrders()
    {
        $user = auth()->user();
        $query = TravelOrder::query()->with('user');

        if ($user->isSuperAdmin()) {
            // Admins see everything including the soft-deleted ones
            return $query->withTrashed()->latest()->paginate(10);
        }

        // Regular users only see their active (non-deleted) orders
        return $query->where('user_id', $user->id)->latest()->paginate(10);
    }

    protected function travelTypes(): array
    {
        return [
            ['name' => 'Intra-Municipal (or within Official Station)',        'id' => 'intra_municipal'],
            ['name' => 'Extra-Municipal (Within Davao de Oro)',               'id' => 'extra_municipal'],
            ['name' => 'Regional (Within Davao Region)',                      'id' => 'regional'],
            ['name' => 'National (Outside Region)',                           'id' => 'national'],
        ];
    }

    #[Computed]
    public function allPositions(): array
    {
        $positions = config('dar_hr.positions') ?? [];
        $formatted = [];

        foreach ($positions as $title => $sg) {
            $formatted[] = [
                'title' => $title,
                'sg'    => "SG-{$sg}",
                'label' => "{$title} (SG-{$sg})" // Example: Driver (SG-4)
            ];
        }

        return $formatted;
    }

    #[Computed]
    public function stationOfficers(): array
    {
        return [
            'OPARO'                   => ['name' => 'Zaldy A. Arenas, MDMG', 'pos' => 'PARPO II'],
            'LTID'                    => ['name' => 'Greg L. Clarin', 'pos' => 'Chief, LTID'],
            'PBDD'                    => ['name' => 'Nancy S. Ramos', 'pos' => 'Chief, PBDD'],
            'Administrative Division' => ['name' => 'Merlina T. Babatid, MExEd, MPA', 'pos' => 'Chief Admin Officer'],
            'Legal Division'          => ['name' => 'Maryrose J. Zulueta', 'pos' => 'Attorney IV'],
            'DARMO-Compostela'        => ['name' => 'Joseto Visaya', 'pos' => 'MARPO'],
            'DARMO-Laak'              => ['name' => 'Avelino O. Tocmo', 'pos' => 'MARPO'],
            'DARMO-Mabini'            => ['name' => 'Greg L. Clarin', 'pos' => 'MARPO'],
            'DARMO-Maco'              => ['name' => 'Dandy B. Barulo', 'pos' => 'MARPO'],
            'DARMO-Maragusan'         => ['name' => 'Eldaliza R. Angcon', 'pos' => 'MARPO'],
            'DARMO-Mawab'             => ['name' => 'Anthony R. Fuerzas', 'pos' => 'OIC MARPO'],
            'DARMO-Monkayo'           => ['name' => 'Noreen Nicolas', 'pos' => 'MARPO'],
            'DARMO-Montevista'        => ['name' => 'Brenda D. Mangco', 'pos' => 'MARPO'],
            'DARMO-Nabunturan'        => ['name' => 'Precy S. Manla', 'pos' => 'MARPO'],
            'DARMO-New Bataan'        => ['name' => 'Ana A. Romanillos', 'pos' => 'MARPO'],
            'DARMO-Pantukan'          => ['name' => 'Allan V. Manuales', 'pos' => 'MARPO'],
        ];
    }

    public function updatedStation($value)
    {
        // Important: Reset destination if they switch towns
        $this->destination = '';
        $this->applyApprovalLogic();
    }

    public function updatedTravelType($value)
    {
        $this->applyApprovalLogic();
    }

    /**
     * Core logic for determining Recommending and Approving Authorities
     */
    private function applyApprovalLogic()
    {
        $officers = $this->stationOfficers();
        $data = $officers[$this->station] ?? null;

        if (!$data) return;

        $parpoName = "Zaldy A. Arenas, MDMG";
        $parpoPos  = "PARPO II";

        // Scenario A: User is in the Provincial Office (OPARO)
        if ($this->station === 'OPARO') {
            $this->recommending_approval = 'N/A';
            $this->recommending_position = '---';
            $this->approved_by_name = $parpoName;
            $this->approved_by_position = $parpoPos;
            return;
        }

        // Scenario B: User is in a DARMO (Municipal Office)
        if (str_starts_with($this->station, 'DARMO-')) {
            if ($this->travel_type === 'intra_municipal') {
                // Within town: MARPO is the final authority
                $this->recommending_approval = 'N/A';
                $this->recommending_position = '---';
                $this->approved_by_name = $data['name'];
                $this->approved_by_position = $data['pos'];
            } else {
                // Outside town: MARPO recommends, PARPO approves
                $this->recommending_approval = $data['name'];
                $this->recommending_position = $data['pos'];
                $this->approved_by_name = $parpoName;
                $this->approved_by_position = $parpoPos;
            }
        }
        // Scenario C: User is in a Provincial Division (LTID, PBDD, etc.)
        else {
            $this->recommending_approval = $data['name'];
            $this->recommending_position = $data['pos'];
            $this->approved_by_name = $parpoName;
            $this->approved_by_position = $parpoPos;
        }
    }

    public function create()
    {
        $this->readOnly = false; // Always editable for new ones
        $this->resetExcept([]);
        $this->purpose_of_trip = [''];
        $user = auth()->user();

        $this->name = $user->fullname;
        $this->position = $user->position ?? '';
        $this->station = $user->station ?? 'DARMO-Mabini'; // Default

        $year = now()->format('Y');
        $month = now()->format('m');
        $prefix = "DARDDO-TO-{$year}-{$month}-";

        $lastOrder = TravelOrder::where('travel_order_no', 'like', $prefix . '%')->orderBy('travel_order_no', 'desc')->first();
        $nextNumber = $lastOrder ? str_pad((int)substr($lastOrder->travel_order_no, -3) + 1, 3, '0', STR_PAD_LEFT) : '001';

        $this->travel_order_no = $prefix . $nextNumber;
        $this->travel_date = now()->format('Y-m-d');
        $this->applyApprovalLogic(); // Set default signatures
        $this->modal = true;
    }

    public function edit(TravelOrder $to)
    {
        // If it's approved and NOT a super admin, force read-only
        $this->readOnly = ($to->status === 'approved' && !auth()->user()->isSuperAdmin());

        $this->editing = $to;
        $this->fill($to->toArray());
        $this->purpose_of_trip = !empty($to->purpose_of_trip) ? $to->purpose_of_trip : [''];
        $this->modal = true;
    }

    public function save()
    {
        // 1. Server-side Security Lock
        if ($this->editing && $this->editing->status === 'approved' && !auth()->user()->isSuperAdmin()) {
            $this->notification()->error('Action Denied', 'This Travel Order is approved and locked.');
            return;
        }

        $this->applyApprovalLogic();

        // 1. Validate ALL fields. If it's not here, it won't be saved!
        $validated = $this->validate([
            'travel_order_no'       => 'required|unique:travel_orders,travel_order_no,' . ($this->editing?->id ?? 'NULL') . ',id',
            'travel_date'           => 'required|date',
            'name'                  => 'required|string',
            'position'              => 'required|string',
            'station'               => 'required|string',
            'travel_type'           => 'required|string',
            'destination'           => 'required|string',
            'departure_date'        => 'required|date',
            'return_date'           => 'required|date',
            'report_to'             => 'nullable|string',
            'purpose_of_trip'       => 'required|array|min:1',
            'transportation_means'  => 'required|string',
            'accommodation_type'    => 'required|string',
            'recommending_approval' => 'required|string',
            'recommending_position' => 'nullable|string',
            'approved_by_name'      => 'required|string',
            'approved_by_position'  => 'required|string',
            'fund_custodian'        => 'required|string',
        ]);

        // 2. Add the custom vehicle logic to the validated data
        $validated['vehicle_type'] = ($this->vehicle_selection === 'others')
            ? $this->custom_vehicle
            : 'government vehicle';

        if ($this->editing) {
            $this->editing->update($validated);
            $this->notification()->success('Updated', 'Travel order updated.');
        } else {
            // 3. Manually add the user_id since it's not in the form
            $validated['user_id'] = auth()->id();

            TravelOrder::create($validated);
            $this->notification()->success('Created', 'New travel order recorded.');
        }

        $this->modal = false;
    }

    public function addPurpose()
    {
        if (empty(trim(end($this->purpose_of_trip)))) {
            $this->notification()->warning('Empty Field', 'Fill the current purpose first.');
            return;
        }
        $this->purpose_of_trip[] = '';
    }

    public function removePurpose($index)
    {
        unset($this->purpose_of_trip[$index]);
        $this->purpose_of_trip = array_values($this->purpose_of_trip);
        if (empty($this->purpose_of_trip)) $this->purpose_of_trip = [''];
    }

    public function delete($id)
    {
        // We use withTrashed() so the Admin can still find it to force-delete
        $order = TravelOrder::withTrashed()->findOrFail($id);
        $user = auth()->user();

        // 1. Logic for Super Admin (Permanent Delete)
        if ($user->isSuperAdmin()) {
            // If it was already soft-deleted, or they just want it gone forever
            $order->forceDelete();
            $this->notification()->success('Permanent Delete', 'Record wiped from database.');
            return;
        }

        // 2. Logic for Regular Users (Soft Delete)
        // Check if they own it and it's not approved
        if ($order->user_id !== $user->id) {
            $this->notification()->error('Unauthorized', 'You can only delete your own orders.');
            return;
        }

        if ($order->status === 'approved') {
            $this->notification()->error('Locked', 'Approved orders cannot be deleted.');
            return;
        }

        $order->delete(); // This is a Soft Delete because of the Trait
        $this->notification()->warning('Archived', 'Order moved to trash.');
    }

    #[Computed]
    public function destinations(): array
    {
        $barangays = config('davao_de_oro.barangays') ?? [];

        // Map the array to include a searchable label
        $formatted = array_map(function($b) {
            return [
                'name'       => $b['name'],
                'municipality' => $b['municipality'],
                'full_label' => "{$b['name']} - {$b['municipality']}" // The magic string
            ];
        }, $barangays);

        if ($this->travel_type === 'intra_municipal') {
            $currentTown = str_replace('DARMO-', '', $this->station);
            return array_values(array_filter($formatted, fn($b) =>
                strtolower($b['municipality']) === strtolower($currentTown)
            ));
        }

        if ($this->travel_type === 'extra_municipal') {
            return array_values($formatted);
        }

        return [];
    }

    public function canEdit($order): bool
    {
        if (auth()->user()->isSuperAdmin()) return true;

        // If it's already approved, regular users cannot edit
        return $order->status === 'pending';
    }

    public function approve($id)
    {
        $order = TravelOrder::findOrFail($id);

        // Safety check: Only the designated approver or admin can click this
        if (str_contains($order->approved_by, auth()->user()->fullname) || auth()->user()->isSuperAdmin()) {
            $order->update(['status' => 'approved']);
            $this->notification()->success('Success', 'Travel Order has been approved and locked.');
        } else {
            $this->notification()->error('Unauthorized', 'You are not the signatory for this order.');
        }
    }

    public function approveOrder($id)
    {
        $order = TravelOrder::findOrFail($id);
        $user = auth()->user();

        // Verification: Is the logged-in user actually the one supposed to sign?
        $isApprover = str_contains($order->approved_by, $user->fullname);
        $isRecommender = str_contains($order->recommending_approval, $user->fullname);

        if ($isApprover || $isRecommender || $user->isSuperAdmin()) {
            $order->update([
                'status' => 'approved',
                'approved_at' => now(),
                // We save a "Digital Stamp" combining their ID and the Time
                'esignature_hash' => md5($user->id . now() . 'DAR-SECRET-KEY')
            ]);

            $this->notification()->success('Travel Order Approved', 'The document is now locked and ready for printing.');
        } else {
            $this->notification()->error('Unauthorized', 'You are not a designated signatory for this Travel Order.');
        }
    }

    public function viewTravelOrder(TravelOrder $to)
    {
        $this->readOnly = true; // Force read-only
        $this->editing = $to;
        $this->fill($to->toArray());
        $this->purpose_of_trip = !empty($to->purpose_of_trip) ? $to->purpose_of_trip : [''];
        $this->modal = true;
    }

    #[Computed]
    public function stationOptions(): array
    {
        return collect($this->stationOfficers())->map(function ($data, $key) {
            return [
                'id'          => $key,   // This becomes 'OPARO', 'LTID', etc.
                'name'        => $key,   // The label shown in the dropdown
                'description' => $data['name'], // Shows the Chief's name as subtext
            ];
        })->values()->toArray();
    }

}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Travel Orders</h1>
        <x-button primary label="New Travel Order" icon="plus" wire:click="create" />
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-secondary-900 shadow-md rounded-xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-secondary-50 dark:bg-secondary-800">
                <tr>
                    <th class="p-4">TO No.</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Type</th> <th class="p-4">Destination</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary-200">
                @foreach($this->travelOrders as $order)
                <tr class="{{ $order->status === 'approved' ? 'bg-green-50/50' : '' }}">
                    {{-- 1. TO NO & STATUS --}}
                    <td class="p-4">
                        {{ $order->travel_order_no }}
                        @if($order->status === 'approved')
                            <x-badge flat success label="Approved" icon="check" class="ml-2" />
                        @else
                            <x-badge flat warning label="Pending" icon="clock" class="ml-2" />
                        @endif
                    </td>

                    {{-- 2. NAME --}}
                    <td class="p-4 font-bold">{{ $order->name }}</td>

                    {{-- 3. TRAVEL TYPE (Logic moved here where $order exists) --}}
                    <td class="p-4">
                        @php
                            $typeColor = [
                                'intra_municipal' => 'blue',
                                'extra_municipal' => 'emerald',
                                'regional'        => 'amber',
                                'national'        => 'rose',
                            ][$order->travel_type] ?? 'secondary';
                        @endphp
                        <x-badge :color="$typeColor"
                                :label="str_replace('_', ' ', $order->travel_type)"
                                flat
                                class="uppercase text-[10px]"
                        />
                    </td>

                    {{-- 4. DESTINATION --}}
                    <td class="p-4">{{ $order->destination }}</td>

                    {{-- 5. ACTIONS --}}
                    <td class="p-4 flex justify-center gap-2">
                        {{-- 1. ALWAYS show View button --}}
                        {{-- This bypasses Livewire and goes straight to the PDF Route --}}
                        {{-- <x-button
                            rounded
                            icon="eye"
                            primary
                            href="{{ route('travel-order.print', $order->id) }}"
                            target="_blank"
                        /> --}}

                        {{-- 2. EDIT & DELETE: Only for the owner AND only if it's NOT approved --}}
                        @if($order->user_id === auth()->id() && $order->status !== 'approved')
                            <x-button rounded icon="pencil" wire:click="edit('{{ $order->id }}')" />

                            @php $isSoftDeleted = $order->trashed(); @endphp

                            <x-button
                                rounded
                                :negative="!$isSoftDeleted"
                                :black="$isSoftDeleted"
                                :icon="$isSoftDeleted ? 'ban' : 'trash'"
                                x-on:confirm="{
                                    title: '{{ $isSoftDeleted ? 'Permanent Delete?' : 'Delete Order?' }}',
                                    description: '{{ $isSoftDeleted ? 'This will wipe the data forever.' : 'This will move the order to trash.' }}',
                                    method: 'delete',
                                    params: '{{ $order->id }}'
                                }"
                            />
                        @endif

                        {{-- 3. APPROVAL: Only for Signatories or Super Admin --}}
                        @if($order->status === 'pending')
                            @php
                                $isSignatory = str_contains($order->approved_by, auth()->user()->fullname) ||
                                            str_contains($order->recommending_approval, auth()->user()->fullname);
                                $isAdmin = auth()->user()->isSuperAdmin();
                            @endphp

                            @if($isSignatory || $isAdmin)
                                <x-button rounded positive icon="check"
                                    x-on:confirm="{
                                        title: 'Approve Order?',
                                        method: 'approveOrder',
                                        params: '{{ $order->id }}'
                                    }"
                                />
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $this->travelOrders->links() }}</div>
    </div>

    <x-modal-card title="Travel Order Form" wire:model="modal" class="w-full md:w-3/4" z-index="z-[500]" persistent>
        <div class="space-y-6"> {{-- Vertical spacing between sections --}}

            {{-- Section 1: Basic Information --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <h3 class="text-sm font-bold text-blue-700 uppercase mb-3 flex items-center gap-2">
                    <x-icon name="user-circle" class="w-4 h-4" /> Personnel & Reference
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input label="Travel Order No." wire:model="travel_order_no" readonly />
                    <x-datetime-picker label="Order Date" wire:model.live="travel_date" without-time :readonly="$readOnly" />
                    <x-input label="Full Name" wire:model="name" :readonly="$readOnly" />
                    <x-select
                        label="Position"
                        wire:model="position"
                        :options="$this->allPositions()"
                        option-label="label"
                        option-value="title"
                        searchable
                        :readonly="$readOnly"
                    />
                </div>
            </div>

            {{-- Section 2: Travel Details --}}
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <h3 class="text-sm font-bold text-green-700 uppercase mb-3 flex items-center gap-2">
                    <x-icon name="map" class="w-4 h-4" /> Itinerary & Scope
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-select
                        label="Official Station"
                        placeholder="Select Official Station"
                        wire:model.live="station"
                        :options="$this->stationOptions"
                        option-label="name"
                        option-value="id"
                        option-description="description"
                        :readonly="$readOnly"
                    />
                    <x-select label="Scope of Travel" wire:model.live="travel_type" :options="$this->travelTypes()" option-label="name" option-value="id" searchable />

                    <div class="md:col-span-2">
                        @if(in_array($travel_type, ['intra_municipal', 'extra_municipal']))
                            <x-select label="Destination (Barangay)" placeholder="Search Barangay..." wire:model="destination" :options="$this->destinations()" option-label="full_label" option-value="name" searchable />
                        @else
                            <x-input label="Destination (City/Province)" placeholder="e.g. Manila" wire:model="destination" icon="map-pin" />
                        @endif
                    </div>

                    <x-datetime-picker label="Departure" wire:model.live="departure_date" :min-date="$this->travel_date" without-time />
                    <x-datetime-picker label="Return" wire:model="return_date" :min-date="$this->departure_date ?: $this->travel_date" without-time />
                </div>
            </div>

            {{-- Section 3: Logistics & Purpose --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-native-select label="Transportation" wire:model="transportation_means" :options="['Land', 'Air', 'Sea']" />
                <x-native-select label="Accommodation" wire:model="accommodation_type" :options="['Live-out', 'Live-in']" />
                <x-input label="Report To" wire:model="report_to" />
            </div>

            <div class="p-3 bg-orange-50 rounded-lg border border-orange-100">
                <label class="text-xs font-bold uppercase mb-2 block text-orange-800">Vehicle Details</label>
                <div class="flex gap-4 mb-2">
                    <x-radio label="Government" value="government vehicle" wire:model.live="vehicle_selection" />
                    <x-radio label="Other" value="others" wire:model.live="vehicle_selection" />
                </div>
                @if($vehicle_selection === 'others')
                    <x-input placeholder="Specify vehicle..." wire:model="custom_vehicle" />
                @endif
            </div>

            {{-- Purpose Repeater --}}
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-2">
                    <label class="font-bold text-gray-700">Purpose of Trip</label>
                    <x-button xs primary label="Add Row" icon="plus" wire:click="addPurpose" />
                </div>
                @foreach($purpose_of_trip as $index => $purpose)
                    <div class="flex gap-2 mb-2">
                        <x-textarea wire:model.blur="purpose_of_trip.{{ $index }}" class="grow" rows="1" />
                        @if(count($purpose_of_trip) > 1)
                            <x-button icon="trash" negative flat wire:click="removePurpose({{ $index }})" />
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Section 4: Signatures (The Split Logic) --}}
            {{-- Section 4: Signatures --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-6 bg-blue-50/50 p-4 rounded-lg">
                {{-- Recommending --}}
                <div class="space-y-2">
                    <x-input label="Recommending Approval" wire:model="recommending_approval" readonly />
                    <x-input label="Position" wire:model="recommending_position" readonly class="text-xs bg-gray-50 italic" />
                </div>

                {{-- Approved By (Synced to DB names) --}}
                <div class="space-y-2">
                    <x-input label="Approved By" wire:model="approved_by_name" readonly />
                    <x-input label="Position" wire:model="approved_by_position" readonly class="text-xs bg-gray-50 italic" />
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-between w-full">
                <div>
                    @if($editing)
                        <x-button
                            xs
                            outline
                            icon="printer"
                            label="Print"
                            href="{{ route('travel-order.print', $order->id) }}"
                            target="_blank"
                        />
                    @endif
                </div>
                <div class="flex gap-x-4">
                    <x-button flat label="Cancel" x-on:click="close" />
                    @if(!$readOnly || auth()->user()->isSuperAdmin())
                        <x-button primary label="Save Travel Order" wire:click="save" spinner="save" />
                    @endif
                </div>
            </div>
        </x-slot>
    </x-modal-card>
</div>
