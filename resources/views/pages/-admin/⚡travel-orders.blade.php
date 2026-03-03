<?php

use App\Models\TravelOrder;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use WireUi\Traits\WireUiActions;

new #[Layout('layouts.app', ['title' => 'Travel Orders'])] class extends Component {
    use WireUiActions;

    public bool $modal = false;
    public ?TravelOrder $editing = null;

    // Form Fields
    public $travel_order_no, $date, $name, $position, $station;
    public $transportation_means = 'land';
    public $vehicle_selection = 'government vehicle';
    public $custom_vehicle = '';
    public $destination, $departure_date, $return_date, $report_to;
    public $purpose_of_trip = []; // Array for checkboxes
    public $accommodation_type = 'live-out';
    public $approved_by, $fund_custodian;

    #[Computed]
    public function travelOrders()
    {
        $query = TravelOrder::query()->with('user');
        // Admin sees all, User sees only theirs
        return auth()->user()->isSuperAdmin() 
            ? $query->latest()->paginate(10) 
            : $query->where('user_id', auth()->id())->latest()->paginate(10);
    }

    public function create()
    {
        $this->resetExcept([]);
        $this->purpose_of_trip = [''];

        // Auto-fill from the current authenticated user
        $user = auth()->user();
        $this->name = $user->fullname; // Matches your User model property
        $this->position = $user->position ?? ''; // Fills position if it exists in your DB
        
        // 1. Get current Year and Month
        $year = now()->format('Y');
        $month = now()->format('m');
        $prefix = "DARDDO-TO-{$year}-{$month}-";

        // 2. Find the last record created THIS month to get the last increment
        $lastOrder = TravelOrder::where('travel_order_no', 'like', $prefix . '%')
            ->orderBy('travel_order_no', 'desc')
            ->first();

        if ($lastOrder) {
            // Extract the last 3 digits, increment by 1
            $lastNumber = (int) substr($lastOrder->travel_order_no, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // First order of the month
            $nextNumber = '001';
        }

        // 3. Set the public property
        $this->travel_order_no = $prefix . $nextNumber;
        $this->date = now()->format('Y-m-d');
        $this->modal = true;

    }

    public function edit(TravelOrder $to)
    {
        $this->editing = $to;
        $this->fill($to->toArray());
        
        // 3. Ensure we have a valid array from the database. 
        // If the DB has null or empty, we force one empty string.
        $this->purpose_of_trip = (!empty($to->purpose_of_trip)) 
            ? $to->purpose_of_trip 
            : [''];

        $this->modal = true;
    }

    public function save()
    {
        $this->validate([
            'travel_order_no' => 'required|unique:travel_orders,travel_order_no,' . ($this->editing?->id ?? 'NULL') . ',id',
            'date' => 'required|date',
            'destination' => 'required',
            'purpose_of_trip' => 'required|array|min:1',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
        ]);

        $vehicle = ($this->vehicle_selection === 'others') ? $this->custom_vehicle : 'government vehicle';

        $data = [
            'travel_order_no' => $this->travel_order_no,
            'date' => $this->date,
            'name' => $this->name,
            'position' => $this->position,
            'station' => $this->station,
            'transportation_means' => $this->transportation_means,
            'vehicle_type' => $vehicle,
            'destination' => $this->destination,
            'departure_date' => $this->departure_date,
            'return_date' => $this->return_date,
            'report_to' => $this->report_to,
            'purpose_of_trip' => $this->purpose_of_trip,
            'accommodation_type' => $this->accommodation_type,
            'approved_by' => $this->approved_by,
            'fund_custodian' => $this->fund_custodian,
        ];

        if ($this->editing) {
            $this->editing->update($data);
            $this->notification()->success('Updated', 'Travel order updated successfully.');
        } else {
            auth()->user()->travelOrders()->create($data);
            $this->notification()->success('Created', 'New travel order recorded.');
        }

        $this->modal = false;
    }

    public function delete($id)
    {
        TravelOrder::find($id)->delete();
        $this->notification()->error('Deleted', 'Order has been removed.');
    }

    #[Computed]
    public function municipalities(): array
    {
        return [
            'OPARO',
            'LTID',
            'PBDD',
            'Administrative Division',
            'Legal Division',
            'DARMO-Compostela', 
            'DARMO-Laak', 
            'DARMO-Mabini', 
            'DARMO-Maco', 
            'DARMO-Maragusan', 
            'DARMO-Mawab', 
            'DARMO-Monkayo', 
            'DARMO-Montevista', 
            'DARMO-Nabunturan', 
            'DARMO-New Bataan', 
            'DARMO-Pantukan'
        ];
    }

    #[Computed]
    public function stationOfficers(): array
    {
        return [
            'OPARO'             => 'Zaldy A. Arenas, MDMG',
            'LTID'              => 'Greg L. Clarin',
            'PBDD'              => 'Nancy S. Ramos',
            'Administrative Division' => 'Merlina T. Babatid, MExEd, MPA',
            'Legal Division'    => 'Maryrose J. Zulueta'  ,
            'DARMO-Compostela' => 'Joseto Visaya',
            'DARMO-Laak'       => 'Avelino O. Tocmo',
            'DARMO-Mabini'     => 'Greg L. Clarin',
            'DARMO-Maco'       => 'Dandy B. Barulo',
            'DARMO-Maragusan'  => 'Eldaliza R. Angcon',
            'DARMO-Mawab'      => 'Anthony R. Fuerzas',
            'DARMO-Monkayo'    => 'Noreen Nicolas',
            'DARMO-Montevista' => 'Brenda D. Mangco',
            'DARMO-Nabunturan' => 'Precy S. Manla', // Example names
            'DARMO-New Bataan' => 'Ana A. Romanillos',
            'DARMO-Pantukan'   => 'Allan V. MAnuales',
        ];
    }

    public function addPurpose()
    {
        // 1. Safety check: If the array is empty for some reason, just add one and stop
        if (empty($this->purpose_of_trip)) {
            $this->purpose_of_trip[] = '';
            return;
        }

        // 2. Get the last index safely
        $lastIndex = count($this->purpose_of_trip) - 1;

        // 3. Check if the last input has content (trim removes spaces/tabs)
        if (empty(trim($this->purpose_of_trip[$lastIndex] ?? ''))) {
            $this->notification()->warning(
                title: 'Empty Field',
                description: 'Please fill out the current purpose before adding another one.'
            );
            return;
        }

        // 4. Add the new blank field
        $this->purpose_of_trip[] = ''; 
    }

    public function removePurpose($index)
    {
        // Remove the specific index
        unset($this->purpose_of_trip[$index]);
        
        // Re-index the array so WireUI doesn't get confused
        $this->purpose_of_trip = array_values($this->purpose_of_trip);

        // Guard rail: Always keep at least one input
        if (empty($this->purpose_of_trip)) {
            $this->purpose_of_trip = [''];
        }
    }

    /**
     * This hook runs every time $station is updated in the browser
     */
    public function updatedStation($value)
    {
        $officers = $this->stationOfficers();

        // If the selected station exists in our map, auto-fill the field
        if (array_key_exists($value, $officers)) {
            $this->approved_by = $officers[$value];
        } else {
            $this->approved_by = ''; // Clear if selection is invalid
        }
    }

}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Travel Orders</h1>
        <x-button primary label="New Travel Order" icon="plus" wire:click="create" />
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-secondary-900 shadow-md rounded-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-secondary-50 dark:bg-secondary-800">
                <tr>
                    <th class="p-4 font-semibold">Travel Order No</th>
                    <th class="p-4 font-semibold">Name</th>
                    <th class="p-4 font-semibold">Destination</th>
                    <th class="p-4 font-semibold">Dates</th>
                    <th class="p-4 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary-200 dark:divide-secondary-700">
                @foreach($this->travelOrders as $order)
                <tr class="hover:bg-secondary-50 dark:hover:bg-secondary-800/50 transition">
                    <td class="p-4 font-medium">{{ $order->travel_order_no }}</td>
                    <td class="p-4">{{ $order->name }}</td>
                    <td class="p-4">{{ $order->destination }}</td>
                    <td class="p-4 text-sm text-secondary-500">
                        {{ $order->departure_date->format('M d') }} - {{ $order->return_date->format('M d, Y') }}
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center gap-2">
                            <x-button rounded icon="pencil" wire:click="edit('{{ $order->id }}')" />
                            <x-button rounded negative icon="trash" 
                                x-on:confirm="{
                                    title: 'Are you sure?',
                                    icon: 'error',
                                    method: 'delete',
                                    params: '{{ $order->id }}'
                                }" 
                            />
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $this->travelOrders->links() }}</div>
    </div>

    {{-- Form Modal --}}
    <x-modal-card z-index="z-[500]" title="{{ $editing ? 'Edit' : 'Create' }} Travel Order" wire:model="modal" max-width="4xl" persistent>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input 
                label="Travel Order No." 
                wire:model="travel_order_no" 
                readonly 
                class="bg-secondary-100 dark:bg-secondary-800 cursor-not-allowed" 
                hint="Automatically generated based on current month"
            />
            <x-datetime-picker label="Travel Order Date" wire:model="date" without-time />
            
            <x-input label="Full Name" wire:model="name" />
            <x-input label="Position" wire:model="position" />
            {{-- Station Dropdown --}}
            <x-select
                label="Offical Station"
                placeholder="Select Your Station"
                wire:model.live="station" 
                :options="array_keys($this->stationOfficers())"
                searchable="true"
            />
            <x-input label="Destination" wire:model="destination" />

            <x-datetime-picker label="Departure Date" wire:model="departure_date" without-time />
            <x-datetime-picker label="Return Date" wire:model="return_date" without-time />

            <x-native-select label="Transportation" wire:model="transportation_means" :options="['Air', 'Land', 'Sea']" />
            <x-native-select label="Accommodation" wire:model="accommodation_type" :options="['Live-in', 'Live-out']" />

            <div class="md:col-span-2 p-4 bg-secondary-50 dark:bg-secondary-800 rounded-lg">
                <div x-data="{ selection: @entangle('vehicle_selection') }" class="space-y-3">
                    <label class="text-sm font-semibold">Vehicle Type</label>
                    <div class="flex gap-4">
                        <x-radio label="Government" value="government vehicle" wire:model="vehicle_selection" />
                        <x-radio label="Others (Specify)" value="others" wire:model="vehicle_selection" />
                    </div>
                    <div x-show="selection === 'others'" x-transition x-cloak>
                        <x-input placeholder="Specify vehicle..." wire:model="custom_vehicle" />
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-4 border-t dark:border-secondary-700 pt-4 mt-2">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-semibold text-secondary-700 dark:text-gray-400">
                        Purpose of Travel
                    </label>
                    <x-button 
                        xs 
                        outline 
                        primary 
                        label="Add Another Purpose" 
                        icon="plus" 
                        wire:click="addPurpose" 
                        spinner="addPurpose"
                    />
                </div>

                <div class="space-y-3">
                    @foreach($purpose_of_trip as $index => $purpose)
                        <div class="flex gap-3 items-start" wire:key="purpose-field-{{ $index }}">
                            <div class="flex-grow">
                                <x-textarea 
                                    placeholder="e.g., To attend the coordination meeting regarding..." 
                                    wire:model.blur="purpose_of_trip.{{ $index }}"
                                    rows="2"
                                />
                            </div>
                            
                            {{-- Only show delete if there's more than one field --}}
                            @if(count($purpose_of_trip) > 1)
                                <div class="pt-2">
                                    <x-button
                                        rounded 
                                        negative 
                                        icon="trash" 
                                        size="sm"
                                        wire:click="removePurpose({{ $index }})" 
                                    />
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                @error('purpose_of_trip.*')
                    <p class="text-sm text-negative-600 mt-1">Please ensure all purpose fields are filled out.</p>
                @enderror
            </div>

            <x-input label="Reports To" wire:model="report_to" />
            {{-- Auto-filled Approved By Field --}}
            <x-input 
                label="Approved By (Immediate Supervisor)" 
                wire:model="approved_by" 
                readonly
                
                hint="Automatically assigned based on Station"
                class="bg-secondary-100 cursor-not-allowed dark:bg-secondary-800"
            />
            <x-input label="Funds Available" wire:model="fund_custodian" class="md:col-span-2" />
        </div>

        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button primary label="Save Travel Order" wire:click="save" spinner="save" />
        </x-slot>
    </x-modal-card>
</div>