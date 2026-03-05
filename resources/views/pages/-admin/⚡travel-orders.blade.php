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
    public $approved_by; 
    public $fund_custodian ='Maria Siezamie B. Agoilo, Budget Officer';
    public $travel_type = 'intra_municipal';
    public $recommending_approval = 'N/a';
    public bool $readOnly = false;

    #[Computed]
    public function travelOrders()
    {
        $user = auth()->user();
        $query = TravelOrder::query()->with('user');

        if ($user->isSuperAdmin()) {
            return $query->latest()->paginate(10);
        }

        // Logic: Show TOs I created OR TOs where MY NAME is in the approval fields
        return $query->where(function($q) use ($user) {
            $q->where('user_id', $user->id)
            ->orWhere('recommending_approval', 'like', "%{$user->fullname}%")
            ->orWhere('approved_by', 'like', "%{$user->fullname}%");
        })->latest()->paginate(10);
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

        // Format the supervisor's name and position
        $supervisor = "{$data['name']}, {$data['pos']}";
        $parpo = "Zaldy A. Arenas, MDMG, PARPO II"; 

        // Specific logic for OPARO (Direct Approval)
        if ($this->station === 'OPARO') {
            $this->recommending_approval = 'N/A';
            $this->approved_by = $parpo;
            return; // Exit early
        }

        // Logic for other Provincial Divisions (Admin, Legal, PBDD, LTID)
        $provincialDivisions = ['LTID', 'PBDD', 'Administrative Division', 'Legal Division'];
        
        if (in_array($this->station, $provincialDivisions)) {
            $this->recommending_approval = $supervisor; // Chief recommends
            $this->approved_by = $parpo;                // PARPO II approves
        } 
        // Logic for Municipal Staff (DARMO)
        else {
            if ($this->travel_type === 'intra_municipal') {
                $this->recommending_approval = 'N/A'; 
                $this->approved_by = $supervisor; // MARPO approves directly
            } else {
                $this->recommending_approval = $supervisor; // MARPO recommends
                $this->approved_by = $parpo;                // PARPO II approves
            }
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
            'approved_by'           => 'required|string',
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
        TravelOrder::find($id)->delete();
        $this->notification()->error('Deleted', 'Order removed.');
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
                        <x-button rounded icon="eye" wire:click="viewTravelOrder('{{ $order->id }}')" />

                        @if($order->status === 'pending')
                            @php
                                $isSignatory = str_contains($order->approved_by, auth()->user()->fullname) || 
                                            str_contains($order->recommending_approval, auth()->user()->fullname);
                                $isAdmin = auth()->user()->isSuperAdmin();
                            @endphp

                            @if($isSignatory || $isAdmin)
                                <x-button rounded label="Aprrove" positive icon="check" 
                                    x-on:confirm="{
                                        title: '{{ $isAdmin ? 'Admin Approval' : 'Approve Travel Order?' }}',
                                        description: 'This will serve as an official electronic signature.',
                                        method: 'approveOrder',
                                        params: '{{ $order->id }}'
                                    }" 
                                />
                            @endif
                        @endif

                        @if($order->status === 'pending' || auth()->user()->isSuperAdmin())
                            <x-button rounded icon="pencil" wire:click="edit('{{ $order->id }}')" />
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $this->travelOrders->links() }}</div>
    </div>

    {{-- Modal --}}
    <x-modal-card title="Travel Order Form" wire:model="modal" class="w-full md:w-3/4" z-index="z-[500]" persistent>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Travel Order No." wire:model="travel_order_no" readonly />
            <x-datetime-picker 
                label="Travel Order Date" 
                wire:model.live="travel_date" 
                without-time
                :readonly="$readOnly" 
                :disabled="$readOnly"
            />
            
            <x-input label="Full Name" wire:model="name" :readonly="$readOnly" 
                :disabled="$readOnly"/>
            <x-select
                label="Position / Designation"
                placeholder="Select or Search Position"
                wire:model="position"
                :options="$this->allPositions()"
                option-label="label"          {{-- Shows: Position (SG-X) --}}
                option-value="title"          {{-- Saves only the Title to your DB --}}
                option-description="sg"       {{-- Small sub-text showing just the SG --}}
                searchable
                :readonly="$readOnly" 
                :disabled="$readOnly"
            />

            <x-select
                label="Official Station"
                placeholder="Select Your Station"
                wire:model.live="station" 
                :options="array_keys($this->stationOfficers())" {{-- This stays the same --}}
                searchable
                :readonly="$readOnly" 
                :disabled="$readOnly"
            />
            
            <x-select
                label="Scope of Travel"
                wire:model.live="travel_type"
                :options="$this->travelTypes()"
                option-label="name"
                option-value="id"
                searchable
                :readonly="$readOnly" 
                :disabled="$readOnly"
            />

            <div class="md:col-span-1">
                @if(in_array($travel_type, ['intra_municipal', 'extra_municipal']))
                    <x-select
                        label="Destination (Barangay)"
                        placeholder="Search by Barangay or Municipality..."
                        wire:model="destination"
                        :options="$this->destinations()"
                        option-label="full_label"    {{-- Shows: Cadunan - Mabini --}}
                        option-value="name"          {{-- Saves ONLY: Cadunan --}}
                        searchable
                        :readonly="$readOnly" 
                        :disabled="$readOnly"
                    />
                @else
                    <x-input 
                        label="Destination (City/Province)" 
                        placeholder="e.g. Manila / Davao City"
                        wire:model="destination" 
                        icon="map-pin" 
                        :readonly="$readOnly" 
                        :disabled="$readOnly"
                    />
                @endif
            </div>
            
            <x-datetime-picker 
                label="Departure Date" 
                wire:model.live="departure_date" 
                :min-date="$this->travel_date" {{-- Use $this here --}}
                without-time
                :readonly="$readOnly" 
                :disabled="$readOnly"
            />
            
            <x-datetime-picker 
                label="Return Date" 
                wire:model="return_date" 
                :min-date="$this->departure_date ?: $this->travel_date" {{-- Use $this here --}}
                without-time
                :readonly="$readOnly" 
                :disabled="$readOnly"
            />
            

            <x-native-select label="Transportation" wire:model="transportation_means" :options="['Land', 'Air', 'Sea']" :readonly="$readOnly" 
                :disabled="$readOnly" />
            <x-native-select label="Accommodation" wire:model="accommodation_type" :options="['Live-out', 'Live-in']" :readonly="$readOnly" 
                :disabled="$readOnly"/>
            <x-input label="Report To" wire:model="report_to" class="md:col-span-1" :readonly="$readOnly" 
                :disabled="$readOnly"/>

            <div class="md:col-span-2 p-3 bg-secondary-50 rounded-lg">
                <label class="text-xs font-bold uppercase mb-2 block">Vehicle Details</label>
                <div class="flex gap-4 mb-2">
                    <x-radio label="Government" value="government vehicle" wire:model.live="vehicle_selection" :readonly="$readOnly" 
                :disabled="$readOnly"/>
                    <x-radio label="Other" value="others" wire:model.live="vehicle_selection" :readonly="$readOnly" 
                :disabled="$readOnly" />
                </div>
                @if($vehicle_selection === 'others')
                    <x-input placeholder="Specify vehicle..." wire:model="custom_vehicle" :readonly="$readOnly" 
                :disabled="$readOnly" />
                @endif
            </div>

            {{-- Purpose Repeater --}}
            <div class="md:col-span-2 border-t pt-4">
                <div class="flex justify-between items-center mb-2">
                    <label class="font-bold">Purpose of Trip</label>
                    <x-button xs primary label="Add Row" icon="plus" wire:click="addPurpose" :readonly="$readOnly" 
                :disabled="$readOnly"/>
                </div>
                @foreach($purpose_of_trip as $index => $purpose)
                    <div class="flex gap-2 mb-2">
                        <x-textarea wire:model.blur="purpose_of_trip.{{ $index }}" class="flex-grow" rows="1" :readonly="$readOnly" 
                :disabled="$readOnly"/>
                        @if(count($purpose_of_trip) > 1)
                            <x-button icon="trash" negative flat wire:click="removePurpose({{ $index }})" :readonly="$readOnly" 
                :disabled="$readOnly" />
                        @endif
                    </div>
                @endforeach
            </div>
            <x-input label="Funds Available" wire:model="fund_custodian" class="md:col-span-1" :readonly="$readOnly" 
                :disabled="$readOnly"/>
            {{-- Signature Section --}}
            <div class="md:col-span-2 grid grid-cols-2 gap-4 border-t pt-4 bg-blue-50/30 p-4 rounded-b-lg">
                <x-input label="Recommending Approval" wire:model="recommending_approval" placeholder="N/A" :readonly="$readOnly" 
                :disabled="$readOnly"/>
                <x-input label="Approved By" wire:model="approved_by" hint="Edit if OIC is designated" :readonly="$readOnly" 
                :disabled="$readOnly" />
            </div>
        
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="Save Travel Order" wire:click="save" spinner="save" :readonly="$readOnly" 
                :disabled="$readOnly"/>
            </div>
        </x-slot>
    </x-modal-card>
</div>