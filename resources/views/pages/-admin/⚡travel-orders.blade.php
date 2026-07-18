<?php

use App\Models\StationOfficer;
use App\Models\TravelOrder;
use EdeesonOpina\PsgcApi\Models\Barangay;
use EdeesonOpina\PsgcApi\Models\CityMunicipality;
use EdeesonOpina\PsgcApi\Models\Province;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

new #[Layout('layouts.app', ['title' => 'Travel Orders'])] class extends Component {
    use WireUiActions, WithPagination;

    public bool $modal = false;
    public ?TravelOrder $editing = null;

    // Form Fields
    public $travel_order_no, $travel_date, $name, $position, $station;
    public $transportation_means = 'Land';
    
    // SYNCED FIELDS
    public $vehicle_type = 'Government Vehicle'; // Default to match PDF logic
    public $vehicle_details = '';
    
    public $destination, $departure_date, $return_date, $report_to;
    public $purpose_of_trip = [];
    public $accommodation_type = 'Live-out'; // Capitalized to match PDF strict check
    public $approved_by_name;
    public $approved_by_position;
    public $fund_custodian ='Maria Siezamie B. Agoilo';
    public $travel_type = 'intra_municipal';
    public $recommending_approval = '';
    public $recommending_position = '';
    public bool $readOnly = false;
    public array $statusFilter = [];
    public string $activeTab = 'mine';
    public string $search = '';

    // Triggered when Vehicle Type changes (WireUI v2)
    public function updatedVehicleType($value)
    {
        if ($value !== 'Others') {
            $this->vehicle_details = ''; // Clear details if switched back to Govt
        }
    }

    #[Computed]
    public function travelOrders()
    {
        return match($this->activeTab) {
            'all'           => $this->allTravelOrders(),
            'mine'          => $this->myTravelOrders(),
            'for_approval'  => $this->forApprovalOrders(),
            'archived'      => $this->archivedTravelOrders(),
            default         => $this->myTravelOrders(),
        };
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->statusFilter = [];
        $this->resetPage();
    }

    private function allTravelOrders()
    {
        $query = TravelOrder::query()->with('user');

        if (!empty($this->statusFilter)) {
            $query->whereIn('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('travel_order_no', 'like', "%{$this->search}%")
                  ->orWhere('name', 'like', "%{$this->search}%")
                  ->orWhere('destination', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    private function myTravelOrders()
    {
        $query = TravelOrder::query()->with('user')
            ->where('user_id', auth()->id());

        if (!empty($this->statusFilter)) {
            $query->whereIn('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('travel_order_no', 'like', "%{$this->search}%")
                  ->orWhere('name', 'like', "%{$this->search}%")
                  ->orWhere('destination', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    private function forApprovalOrders()
    {
        $user = auth()->user();
        $fullName = $user->full_name;

        $query = TravelOrder::query()->with('user')
            ->where('status', 'pending')
            ->where(function ($q) use ($fullName) {
                $q->where('approved_by_name', 'like', "%{$fullName}%")
                  ->orWhere('recommending_approval', 'like', "%{$fullName}%");
            });

        if (! $user->isSuperAdmin()) {
            $query->where('user_id', '!=', $user->id);
        }

        if (!empty($this->statusFilter)) {
            $query->whereIn('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('travel_order_no', 'like', "%{$this->search}%")
                  ->orWhere('name', 'like', "%{$this->search}%")
                  ->orWhere('destination', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    private function archivedTravelOrders()
    {
        $query = TravelOrder::withTrashed()->with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('travel_order_no', 'like', "%{$this->search}%")
                  ->orWhere('name', 'like', "%{$this->search}%")
                  ->orWhere('destination', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    public function setStatusFilter(?string $status): void
    {
        if ($status === null) {
            $this->statusFilter = [];
        } elseif (in_array($status, $this->statusFilter)) {
            $this->statusFilter = array_values(array_diff($this->statusFilter, [$status]));
        } else {
            $this->statusFilter[] = $status;
        }
        $this->resetPage();
    }

    protected function travelTypes(): array 
    {
        return [
            ['name' => 'Intra-Municipal (or within Official Station)',        'id' => 'intra_municipal'],
            ['name' => 'Extra-Municipal (Within Davao de Oro)',               'id' => 'extra_municipal'],
            ['name' => 'Regional (Within Davao Region)',                      'id' => 'regional'],
            ['name' => 'National (Outside Region)',                           'id' => 'national', 'disabled' => true],
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
        return StationOfficer::ordered()
            ->get()
            ->mapWithKeys(fn($officer) => [
                $officer->station_code => [
                    'name' => $officer->officer_name,
                    'suffix' => $officer->academic_suffix,
                    'pos' => $officer->position,
                ],
            ])
            ->toArray();
    }

    private function formatNameWithSuffix(array $data): string
    {
        return $data['suffix']
            ? "{$data['name']}, {$data['suffix']}"
            : $data['name'];
    }

    public function updatedStation($value)
    {
        // Important: Reset destination if they switch towns
        $this->destination = '';
        $this->applyApprovalLogic();
    }

    public function updatedTravelType($value)
    {
        $this->destination = '';
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

        // PARPO II is always read from the live station_officers table (OPARO station)
        $parpoData = $officers['OPARO'] ?? null;
        $parpoName = $this->formatNameWithSuffix($parpoData ?? ['name' => 'Zaldy A. Arenas', 'suffix' => 'MDMG']);
        $parpoPos  = $parpoData['pos'] ?? 'PARPO II';

        // Approved By is always the station officer
        $this->approved_by_name = $this->formatNameWithSuffix($data);
        $this->approved_by_position = $data['pos'];

        // Scenario A: User is in the Provincial Office (OPARO)
        if ($this->station === 'OPARO') {
            $this->recommending_approval = 'N/A';
            $this->recommending_position = '---';
            return;
        }

        // Scenario B: User is in a DARMO (Municipal Office)
        if (str_starts_with($this->station, 'DARMO-')) {
            if ($this->travel_type === 'intra_municipal') {
                // Within town: MARPO is the final authority
                $this->recommending_approval = 'N/A';
                $this->recommending_position = '---';
            } else {
                // Outside town: MARPO recommends, PARPO approves
                $this->recommending_approval = $this->formatNameWithSuffix($data);
                $this->recommending_position = $data['pos'];
                $this->approved_by_name = $parpoName;
                $this->approved_by_position = $parpoPos;
            }
        }
        // Scenario C: User is in a Provincial Division (LTID, PBDD, Administrative, Legal)
        else {
            if ($this->travel_type === 'intra_municipal') {
                // Within province: Division Chief is the final authority
                $this->recommending_approval = 'N/A';
                $this->recommending_position = '---';
            } else {
                // Outside province: Division Chief recommends, PARPO approves
                $this->recommending_approval = $this->formatNameWithSuffix($data);
                $this->recommending_position = $data['pos'];
                $this->approved_by_name = $parpoName;
                $this->approved_by_position = $parpoPos;
            }
        }
    }

    public function create()
    {
        $this->readOnly = false; // Always editable for new ones
        $this->resetExcept([]);
        $this->purpose_of_trip = [''];
        $user = auth()->user();

        $this->name = $user->full_name;
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
        $this->readOnly = ($to->status === 'approved' && !auth()->user()->isSuperAdmin());
        $this->editing = $to;
        
        // Fill all fields from the database
        $this->fill($to->toArray());

        // Refresh approval fields from live station_officers data
        $this->applyApprovalLogic();
        
        // Ensure "Others" visibility logic works immediately upon opening
        $this->vehicle_type = $to->vehicle_type === 'Government Vehicle' ? 'Government Vehicle' : 'Others';
        
        // If it was "Others", the actual value from the DB is stored in vehicle_type, 
        // so we move it to details for the input field.
        if ($this->vehicle_type === 'Others') {
            $this->vehicle_details = $to->vehicle_type; 
        }

        $this->purpose_of_trip = !empty($to->purpose_of_trip) ? $to->purpose_of_trip : [''];
        $this->modal = true;
    }

    public function save()
    {
        if ($this->editing && $this->editing->status === 'approved' && !auth()->user()->isSuperAdmin()) {
            $this->notification()->error('Action Denied', 'This Travel Order is locked.');
            return;
        }

        $this->applyApprovalLogic();

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
            'purpose_of_trip'       => 'required|array|min:1',
            'transportation_means'  => 'required|string',
            'accommodation_type'    => 'required|string',
            'recommending_approval' => 'required|string',
            'approved_by_name'      => 'required|string',
            'approved_by_position'  => 'required|string',
            'fund_custodian'        => 'required|string',
            'report_to'             => 'required|string',
        ]);

        // SYNC LOGIC: If 'Others', save the specific details into the vehicle_type column
        $finalVehicleValue = ($this->vehicle_type === 'Others') 
            ? $this->vehicle_details 
            : 'Government Vehicle';

        $data = array_merge($validated, [
            'vehicle_type' => $finalVehicleValue,
            'recommending_position' => $this->recommending_position,
            'user_id' => auth()->id()
        ]);

        if ($this->editing) {
            $this->editing->update($data);
            $this->notification()->success('Updated', 'Travel order updated.');
        } else {
            TravelOrder::create($data);
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

    public function deleteOrder($id)
    {
        $order = TravelOrder::withTrashed()->findOrFail($id);
        $user = auth()->user();

        // 1. Privileged roles (super admin & admin) may soft-delete ANY travel order
        if (! ($user->isSuperAdmin() || $user->isAdmin())) {
            // 2. Regular users may only soft-delete their own, non-approved orders
            if ($order->user_id !== $user->id) {
                return $this->notification()->error('Unauthorized', 'You can only delete your own orders.');
            }

            if ($order->status === 'approved') {
                return $this->notification()->error('Locked', 'Approved orders cannot be deleted.');
            }
        }

        $this->dialog()->confirm([
            'title'       => 'Delete Travel Order?',
            'description' => 'This will permanently remove travel order no. ' . $order->travel_order_no . '. This action cannot be undone.',
            'icon'        => 'trash',
            'accept'      => [
                'label'  => 'Delete',
                'color'  => 'negative',
                'method' => 'performDelete',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performDelete($id)
    {
        $order = TravelOrder::withTrashed()->findOrFail($id);
        $order->delete();
        $this->notification()->warning('Archived', 'Travel order moved to trash.');
    }

    public function restoreOrder($id)
    {
        if (! auth()->user()->isSuperAdmin()) {
            return $this->notification()->error('Unauthorized', 'Only super admins can restore archived orders.');
        }

        $order = TravelOrder::withTrashed()->findOrFail($id);

        $this->dialog()->confirm([
            'title'       => 'Restore Travel Order?',
            'description' => 'This will restore travel order no. ' . $order->travel_order_no . ' back to the active list.',
            'icon'        => 'arrow-uturn-left',
            'accept'      => [
                'label'  => 'Restore',
                'color'  => 'positive',
                'method' => 'performRestore',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performRestore($id)
    {
        $order = TravelOrder::withTrashed()->findOrFail($id);
        $order->restore();
        $this->notification()->success('Restored', 'Travel order has been restored.');
    }

    public function forceDeleteOrder($id)
    {
        if (! auth()->user()->isSuperAdmin()) {
            return $this->notification()->error('Unauthorized', 'Only super admins can perform this action.');
        }

        $order = TravelOrder::withTrashed()->findOrFail($id);

        $this->dialog()->confirm([
            'title'       => 'Permanently Delete?',
            'description' => 'This will permanently delete travel order no. ' . $order->travel_order_no . '. This action cannot be undone.',
            'icon'        => 'trash',
            'accept'      => [
                'label'  => 'Delete Permanently',
                'color'  => 'negative',
                'method' => 'performForceDelete',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performForceDelete($id)
    {
        $order = TravelOrder::withTrashed()->findOrFail($id);
        $order->forceDelete();
        $this->notification()->warning('Deleted', 'Travel order has been permanently deleted.');
    }

    public function deleteAllArchived()
    {
        if (! auth()->user()->isSuperAdmin()) {
            return $this->notification()->error('Unauthorized', 'Only super admins can perform this action.');
        }

        $count = TravelOrder::onlyTrashed()->count();

        if ($count === 0) {
            return $this->notification()->info('Nothing to Delete', 'There are no archived travel orders.');
        }

        $this->dialog()->confirm([
            'title'       => 'Permanently Delete All?',
            'description' => "This will permanently delete {$count} archived travel order(s). This action cannot be undone.",
            'icon'        => 'trash',
            'accept'      => [
                'label'  => 'Delete All',
                'color'  => 'negative',
                'method' => 'performDeleteAllArchived',
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performDeleteAllArchived()
    {
        TravelOrder::onlyTrashed()->forceDelete();
        $this->notification()->warning('Deleted', 'All archived travel orders have been permanently deleted.');
    }

    public function destinations(): array
    {
        // Map headquarters offices to Nabunturan (their municipality)
        $stationMunicipalityMap = [
            'OPARO'                   => 'Nabunturan',
            'LTID'                    => 'Nabunturan',
            'PBDD'                    => 'Nabunturan',
            'Administrative Division' => 'Nabunturan',
            'Legal Division'          => 'Nabunturan',
        ];

        if ($this->travel_type === 'intra_municipal') {
            $currentTown = $stationMunicipalityMap[$this->station] ?? str_replace('DARMO-', '', $this->station);

            $barangays = Barangay::query()
                ->join('city_municipalities', 'barangays.city_municipality_id', '=', 'city_municipalities.id')
                ->where('barangays.province_id', 58) // Davao de Oro
                ->where('city_municipalities.name', $currentTown)
                ->where('city_municipalities.province_id', 58) // Ensure correct Mabini
                ->orderBy('barangays.name')
                ->get(['barangays.name', 'city_municipalities.name as municipality_name'])
                ->map(fn($b) => [
                    'name'        => $b->name,
                    'municipality' => $b->municipality_name,
                    'full_label'  => "{$b->name}, {$b->municipality_name}, Davao de Oro",
                ])
                ->toArray();

            return $barangays;
        }

        if ($this->travel_type === 'extra_municipal') {
            $barangays = Barangay::query()
                ->join('city_municipalities', 'barangays.city_municipality_id', '=', 'city_municipalities.id')
                ->where('barangays.province_id', 58) // Davao de Oro
                ->orderBy('city_municipalities.name')
                ->orderBy('barangays.name')
                ->get(['barangays.name', 'city_municipalities.name as municipality_name'])
                ->map(fn($b) => [
                    'name'        => $b->name,
                    'municipality' => $b->municipality_name,
                    'full_label'  => "{$b->name}, {$b->municipality_name}, Davao de Oro",
                ])
                ->toArray();

            return $barangays;
        }

        if ($this->travel_type === 'regional') {
            // Get the region and province of the selected station (scoped to Davao de Oro)
            $currentTown = $stationMunicipalityMap[$this->station] ?? str_replace('DARMO-', '', $this->station);

            $station = CityMunicipality::query()
                ->where('name', $currentTown)
                ->where('province_id', 58) // Davao de Oro
                ->first(['province_id', 'region_id']);

            if (!$station) {
                return [];
            }

            // Get all barangays in the same region, excluding the station's province
            $destinations = Barangay::query()
                ->join('city_municipalities', 'barangays.city_municipality_id', '=', 'city_municipalities.id')
                ->join('provinces', 'city_municipalities.province_id', '=', 'provinces.id')
                ->where('city_municipalities.region_id', $station->region_id)
                ->where('city_municipalities.province_id', '!=', $station->province_id)
                ->orderBy('provinces.name')
                ->orderBy('city_municipalities.name')
                ->orderBy('barangays.name')
                ->get(['barangays.name', 'city_municipalities.name as municipality_name', 'provinces.name as province_name'])
                ->map(fn($b) => [
                    'name'        => $b->name,
                    'municipality' => $b->municipality_name,
                    'full_label'  => $b->municipality_name !== $b->province_name
                        ? "{$b->name}, {$b->municipality_name}, {$b->province_name}"
                        : "{$b->name}, {$b->municipality_name}",
                ])
                ->toArray();

            return $destinations;
        }

        if ($this->travel_type === 'national') {
            // Get the region of the selected station (scoped to Davao de Oro)
            $currentTown = $stationMunicipalityMap[$this->station] ?? str_replace('DARMO-', '', $this->station);

            $stationRegionId = CityMunicipality::query()
                ->where('name', $currentTown)
                ->where('province_id', 58) // Davao de Oro
                ->value('region_id');

            if (!$stationRegionId) {
                return [];
            }

            // Get all barangays across all provinces, excluding the station's region
            $destinations = Barangay::query()
                ->join('city_municipalities', 'barangays.city_municipality_id', '=', 'city_municipalities.id')
                ->join('provinces', 'city_municipalities.province_id', '=', 'provinces.id')
                ->where('city_municipalities.region_id', '!=', $stationRegionId)
                ->orderBy('provinces.name')
                ->orderBy('city_municipalities.name')
                ->orderBy('barangays.name')
                ->get(['barangays.name', 'city_municipalities.name as municipality_name', 'provinces.name as province_name'])
                ->map(fn($b) => [
                    'name'        => $b->name,
                    'municipality' => $b->municipality_name,
                    'full_label'  => $b->municipality_name !== $b->province_name
                        ? "{$b->name}, {$b->municipality_name}, {$b->province_name}"
                        : "{$b->name}, {$b->municipality_name}",
                ])
                ->toArray();

            return $destinations;
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
        // Redirect to approveOrder — this method is kept for backward compatibility
        $this->approveOrder($id);
    }

    public function approveOrder($id)
    {
        $order = TravelOrder::findOrFail($id);
        $user = auth()->user();

        $isApprover = str_contains($order->approved_by_name, $user->full_name);

        if (!$isApprover) {
            return $this->notification()->error('Unauthorized', 'You are not a designated signatory for this Travel Order.');
        }

        $this->dialog()->confirm([
            'title'       => 'Approve Order?',
            'description' => 'This will approve travel order no. ' . $order->travel_order_no . '. The document will be locked.',
            'icon'        => 'check',
            'accept'      => [
                'label'  => 'Approve',
                'color'  => 'positive',
                'method' => 'performApproveOrder',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performApproveOrder($id)
    {
        $order = TravelOrder::findOrFail($id);
        $user = auth()->user();
        $signature = $user->activeSignature();

        if (!$signature) {
            return $this->notification()->error('No Signature', 'Please create and activate a digital signature first.');
        }

        $order->update([
            'status' => 'approved',
            'approved_at' => now(),
            'esignature_hash' => $signature->esignature_hash,
        ]);

        $this->notification()->success('Travel Order Approved', 'The document is now locked and ready for printing.');
    }

    public function disapproveOrder($id)
    {
        $order = TravelOrder::findOrFail($id);
        $user = auth()->user();

        $isApprover = str_contains($order->approved_by_name, $user->full_name);

        if (!$isApprover) {
            return $this->notification()->error('Unauthorized', 'You are not a designated signatory for this Travel Order.');
        }

        $order->update([
            'status' => 'disapproved',
        ]);

        $this->notification()->warning('Travel Order Disapproved', 'The document has been marked as disapproved.');
    }

    public function recommendOrder($id)
    {
        $order = TravelOrder::findOrFail($id);
        $user = auth()->user();

        $isRecommender = str_contains($order->recommending_approval, $user->full_name);

        if (!$isRecommender) {
            return $this->notification()->error('Unauthorized', 'You are not the designated recommender for this Travel Order.');
        }

        $this->dialog()->confirm([
            'title'       => 'Recommend Order?',
            'description' => 'This will record your recommendation for travel order no. ' . $order->travel_order_no . '.',
            'icon'        => 'finger-print',
            'accept'      => [
                'label'  => 'Confirm',
                'color'  => 'info',
                'method' => 'performRecommendOrder',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performRecommendOrder($id)
    {
        $order = TravelOrder::findOrFail($id);
        $user = auth()->user();
        $signature = $user->activeSignature();

        if (!$signature) {
            return $this->notification()->error('No Signature', 'Please create and activate a digital signature first.');
        }

        $order->update([
            'recommending_approved_at' => now(),
            'esignature_recommender_hash' => $signature->esignature_hash,
        ]);

        $this->notification()->success('Recommendation Recorded', 'The recommender has signed off on this travel order.');
    }

    public function revertApproval($id)
    {
        $order = TravelOrder::findOrFail($id);

        if (!auth()->user()->isSuperAdmin()) {
            return $this->notification()->error('Unauthorized', 'Only super admins can revert approvals.');
        }

        $this->dialog()->confirm([
            'title'       => 'Revert Approval?',
            'description' => 'This will reset travel order no. ' . $order->travel_order_no . ' back to pending status.',
            'icon'        => 'arrow-uturn-left',
            'accept'      => [
                'label'  => 'Revert',
                'color'  => 'warning',
                'method' => 'performRevertApproval',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performRevertApproval($id)
    {
        $order = TravelOrder::findOrFail($id);

        $order->update([
            'status' => 'pending',
            'approved_at' => null,
            'esignature_hash' => null,
            'recommending_approved_at' => null,
            'esignature_recommender_hash' => null,
        ]);

        $this->notification()->success('Approval Reverted', 'Travel order has been reset to pending.');
    }

    public function revertRecommendation($id)
    {
        $order = TravelOrder::findOrFail($id);

        if (!auth()->user()->isSuperAdmin()) {
            return $this->notification()->error('Unauthorized', 'Only super admins can revert recommendations.');
        }

        $this->dialog()->confirm([
            'title'       => 'Revert Recommendation?',
            'description' => 'This will remove the recommender sign-off for travel order no. ' . $order->travel_order_no . '.',
            'icon'        => 'arrow-uturn-left',
            'accept'      => [
                'label'  => 'Revert',
                'color'  => 'warning',
                'method' => 'performRevertRecommendation',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performRevertRecommendation($id)
    {
        $order = TravelOrder::findOrFail($id);

        $order->update([
            'recommending_approved_at' => null,
            'esignature_recommender_hash' => null,
        ]);

        $this->notification()->success('Recommendation Reverted', 'The recommender sign-off has been removed.');
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

<x-main-container 
    title="Travel Orders" 
    :breadcrumbs="[
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => 'Travel Orders']
    ]"
>
    {{-- Tabs --}}
    @php $isSuperAdmin = auth()->user()->isSuperAdmin(); @endphp
    <div role="tablist" class="trapezoid-tabs">
        @if($isSuperAdmin)
            <button role="tab" class="trapezoid-tab {{ $activeTab === 'all' ? 'trapezoid-tab-active' : '' }}"
                wire:click="setTab('all')">
                <x-icon name="globe-alt" class="w-4 h-4 shrink-0" /> All Travel Orders
            </button>
        @endif
        <button role="tab" class="trapezoid-tab {{ $activeTab === 'mine' ? 'trapezoid-tab-active' : '' }}"
            wire:click="setTab('mine')">
            <x-icon name="user" class="w-4 h-4 shrink-0" /> My Travel Orders
        </button>
        <button role="tab" class="trapezoid-tab {{ $activeTab === 'for_approval' ? 'trapezoid-tab-active' : '' }}"
            wire:click="setTab('for_approval')">
            <x-icon name="clipboard-document-check" class="w-4 h-4 shrink-0" /> For Approval
        </button>
        @if($isSuperAdmin)
            <button role="tab" class="trapezoid-tab {{ $activeTab === 'archived' ? 'trapezoid-tab-active' : '' }}"
                wire:click="setTab('archived')">
                <x-icon name="archive-box" class="w-4 h-4 shrink-0" /> Archived
            </button>
        @endif
    </div>

    {{-- Status Filters --}}
    @if($activeTab !== 'archived')
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                <input type="checkbox" class="checkbox checkbox-sm checkbox-primary"
                    checked="{{ empty($statusFilter) }}"
                    wire:click="setStatusFilter(null)" />
                <x-icon name="funnel" class="w-4 h-4" /> All
            </label>
            <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                <input type="checkbox" class="checkbox checkbox-sm checkbox-warning"
                    checked="{{ in_array('pending', $statusFilter) }}"
                    wire:click="setStatusFilter('pending')" />
                <x-icon name="clock" class="w-4 h-4" /> Pending
            </label>
            <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                <input type="checkbox" class="checkbox checkbox-sm checkbox-success"
                    checked="{{ in_array('approved', $statusFilter) }}"
                    wire:click="setStatusFilter('approved')" />
                <x-icon name="check" class="w-4 h-4" /> Approved
            </label>
            <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                <input type="checkbox" class="checkbox checkbox-sm checkbox-error"
                    checked="{{ in_array('disapproved', $statusFilter) }}"
                    wire:click="setStatusFilter('disapproved')" />
                <x-icon name="x-mark" class="w-4 h-4" /> Disapproved
            </label>
        </div>

        @if($activeTab === 'mine')
            <x-button primary label="New Travel Order" icon="plus" wire:click="create" />
        @endif
    </div>
    @else
    <div class="flex justify-end items-center mb-4">
        <x-button negative label="Empty Archive" icon="trash" wire:click="deleteAllArchived" />
    </div>
    @endif

    {{-- Search --}}
    <div class="mb-4">
        <x-input icon="magnifying-glass" placeholder="Search travel orders..."
            wire:model.live.debounce.300ms="search" shadow="none" />
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
                <tr class="{{ $order->status === 'approved' ? 'bg-green-50/50' : ($order->status === 'disapproved' ? 'bg-red-50/50' : '') }}">
                    {{-- 1. TO NO & STATUS --}}
                    <td class="p-4">
                        {{ $order->travel_order_no }}
                        @if($order->status === 'approved')
                            <x-badge flat success label="Approved" icon="check" class="ml-2" />
                        @elseif($order->status === 'disapproved')
                            <x-badge flat error label="Disapproved" icon="x-mark" class="ml-2" />
                        @else
                            <x-badge flat warning label="Pending" icon="clock" class="ml-2" />
                        @endif
                        @if($order->travel_type !== 'intra_municipal' && !empty($order->recommending_approval) && $order->recommending_approval !== 'N/A')
                            @if($order->recommending_approved_at)
                                <x-badge flat info label="Recommender Signed" icon="finger-print" class="ml-1" />
                            @else
                                <x-badge flat neutral label="Awaiting Recommender" icon="finger-print" class="ml-1" />
                            @endif
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
                    <td class="p-4">{{ $order->formattedDestination() }}</td>

                    {{-- 5. ACTIONS --}}
                    <td class="p-4 flex justify-center gap-2">
                        {{-- VIEW: Open the PDF/print view for everyone --}}
                        <x-button rounded info icon="eye" class="tooltip" data-tip="View"
                            href="{{ route('admin.print-travel-order', $order->id) }}"
                            target="_blank" />

                        {{-- PRINT: Available for any record via the print view --}}
                        <x-button sm icon="printer" class="tooltip" data-tip="Print"
                            href="{{ route('admin.print-travel-order', $order->id) }}"
                            target="_blank" />

                        @if($activeTab === 'archived')
                            {{-- RESTORE: Super admin can restore archived orders --}}
                            <x-button rounded positive icon="arrow-uturn-left" wire:click="restoreOrder('{{ $order->id }}')" class="tooltip" data-tip="Restore" />

                            {{-- PERMANENT DELETE: Super admin can permanently delete archived orders --}}
                            <x-button rounded negative icon="trash" wire:click="forceDeleteOrder('{{ $order->id }}')" class="tooltip" data-tip="Delete Permanently" />
                        @else
                            {{-- EDIT & DELETE: For owner if not approved, OR for privileged roles (super admin / admin) --}}
                            @if(($order->user_id === auth()->id() && $order->status !== 'approved') || auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                <x-button rounded icon="pencil" wire:click="edit('{{ $order->id }}')" class="tooltip" data-tip="Edit" />

                                <x-button
                                    rounded
                                    negative
                                    icon="trash"
                                    class="tooltip"
                                    data-tip="Delete"
                                    wire:click="deleteOrder('{{ $order->id }}')"
                                />
                            @endif

                            {{-- PRINT REVERT: Super admin can revert an approved order --}}
                            @if($order->status === 'approved' && auth()->user()->isSuperAdmin())
                                <x-button rounded warning icon="arrow-uturn-left"
                                    class="tooltip" data-tip="Revert to Pending"
                                    wire:click="revertApproval('{{ $order->id }}')"
                                />
                            @endif

                            {{-- APPROVAL: Only for Signatories or Super Admin --}}
                            @if($order->status === 'pending')
                                @php
                                    $needsRecommendation = $order->travel_type !== 'intra_municipal' &&
                                        !empty($order->recommending_approval) && $order->recommending_approval !== 'N/A';
                                    $hasRecommended = !empty($order->recommending_approved_at);
                                    $isRecommender = str_contains($order->recommending_approval, auth()->user()->full_name);
                                    $isApprover = str_contains($order->approved_by_name, auth()->user()->full_name);
                                    $isAdmin = auth()->user()->isSuperAdmin();

                                    // Recommend button: needs recommendation, not yet done, user is the named recommender
                                    $canRecommend = $needsRecommendation && !$hasRecommended && $isRecommender;

                                    // Approve button: only the named approver (or recommender for intra-municipal
                                    // where recommending is N/A) may approve. Super admin is no longer given a
                                    // blanket approve right — they must be the named authority on the order.
                                    $canApprove = $isApprover || $isRecommender;
                                @endphp

                                @if($canRecommend)
                                    <x-button rounded info icon="finger-print"
                                        class="tooltip" data-tip="Recommend"
                                        wire:click="recommendOrder('{{ $order->id }}')"
                                    />
                                @endif

                                @if($needsRecommendation && $hasRecommended && $isAdmin)
                                    <x-button rounded warning icon="arrow-uturn-left"
                                        class="tooltip" data-tip="Revert Recommendation"
                                        wire:click="revertRecommendation('{{ $order->id }}')"
                                    />
                                @endif

                                @if($canApprove)
                                    <x-button rounded positive icon="check"
                                        class="tooltip" data-tip="Approve"
                                        wire:click="approveOrder('{{ $order->id }}')"
                                    />
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $this->travelOrders->links() }}</div>
    </div>

    <!-- CREATE / EDIT MODAL -->
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
                        @if(in_array($travel_type, ['intra_municipal', 'extra_municipal', 'regional', 'national']))
                            <x-select label="Destination" placeholder="Search destination..." wire:model="destination" :options="$this->destinations()" option-label="full_label" option-value="name" searchable />
                        @endif
                    </div>

                    <x-datetime-picker label="Departure" wire:model.live="departure_date" :min-date="$this->travel_date" without-time />
                    <x-datetime-picker label="Return" wire:model="return_date" :min-date="$this->departure_date ?: $this->travel_date" without-time />
                </div>
            </div>

            {{-- Reports To Field --}}
            <div class="md:col-span-3" >
                <x-input 
                    wire:model="report_to" 
                    label="Report to:" 
                    placeholder="e.g. Person of interest for travel"
                    icon="user"
                    :readonly="$readOnly"
                />
            </div>

            {{-- Section 3: Logistics & Purpose --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-orange-50/30 p-4 rounded-lg border border-orange-100">
                
                {{-- 1. Means of Trans --}}
                <x-select
                    label="Means of Transportation"
                    placeholder="Select mode"
                    wire:model.live="transportation_means" 
                    :readonly="$readOnly"
                >
                    <x-select.option label="Air" value="Air" />
                    <x-select.option label="Land" value="Land" />
                    <x-select.option label="Sea" value="Sea" />
                </x-select>

                {{-- 2. Accommodation --}}
                <x-select
                    label="Accommodation"
                    placeholder="Select type"
                    wire:model.live="accommodation_type"
                    :readonly="$readOnly"
                >
                    <x-select.option label="Live-in" value="Live-in" />
                    <x-select.option label="Live-out" value="Live-out" />
                    <x-select.option label="N/A" value="N/A" />
                </x-select>

                {{-- 3. Vehicle Ownership --}}
                <x-select
                    label="Vehicle Ownership"
                    placeholder="Select ownership"
                    wire:model.live="vehicle_type"
                    :readonly="$readOnly"
                >
                    <x-select.option label="Government Vehicle" value="Government Vehicle" />
                    <x-select.option label="Others (Specify...)" value="Others" />
                </x-select>

                {{-- Conditional Details Field --}}
                <div class="md:col-span-3" wire:key="vehicle-details-area">
                    @if($vehicle_type === 'Others')
                        <x-input 
                            wire:model="vehicle_details" 
                            label="Vehicle Details" 
                            placeholder="e.g. Public Utility Jeep, Rented Van"
                            icon="truck"
                            :readonly="$readOnly"
                        />
                    @endif
                </div>
                
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

            {{-- Section 4: Signatures --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-6 bg-blue-50/50 p-4 rounded-lg">
                {{-- Recommending --}}
                @if(!empty($recommending_approval) && $recommending_approval !== 'N/A')
                <div class="space-y-2">
                    <x-input label="Recommending Approval" wire:model="recommending_approval" readonly />
                    <x-input label="Position" wire:model="recommending_position" readonly class="text-xs bg-gray-50 italic" />
                </div>
                @endif

                {{-- Approved By (Synced to DB names) --}}
                <div class="space-y-2">
                    <x-input label="Approved By" wire:model="approved_by_name" readonly />
                    <x-input label="Position" wire:model="approved_by_position" readonly class="text-xs bg-gray-50 italic" />
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-between w-full">
                <div class="flex gap-2">
                    @if($this->editing)
                        <x-button 
                            sm 
                            icon="printer" 
                            label="Print Preview" 
                            href="{{ route('admin.print-travel-order', $this->editing->id) }}" 
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
    <!-- END OF CREATE / EDIT MODAL -->

</x-main-container>
