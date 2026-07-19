<?php

use App\Models\StationOfficer;
use App\Models\OfficialStation;
use App\Models\User;
use App\Helpers\PositionHelper;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

new #[Layout('layouts.app')]
    #[Title('Station Officers')]
    class extends Component {
    use WithPagination, WireUiActions;

    public string $search = '';
    public bool $modal = false;
    public ?StationOfficer $editing = null;

    public string $station_code = '';
    public string $officer_name = '';
    public ?string $academic_suffix = null;
    public ?string $user_id = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\Computed]
    public function officers()
    {
        return StationOfficer::query()
            ->with('user.profile')
            ->when($this->search, fn($q) => $q->where('station_code', 'like', "%{$this->search}%")
                ->orWhere('officer_name', 'like', "%{$this->search}%")
                ->orWhere('academic_suffix', 'like', "%{$this->search}%"))
            ->ordered()
            ->paginate(15);
    }

    #[\Livewire\Attributes\Computed]
    public function eligibleUsers(): array
    {
        if (!$this->station_code) {
            return [];
        }

        return User::query()
            ->where(function ($q) {
                // Match by station assignment
                $q->whereHas('profile', function ($q) {
                    $q->where('area_of_assignment', $this->station_code);
                });

                // Also include users with MARPO-related position (for DARMO stations)
                if (str_starts_with($this->station_code, 'DARMO-')) {
                    $q->orWhereHas('profile', function ($q) {
                        $q->where('position', 'like', '%MARPO%');
                    });
                }

                // Include users with OIC MARPO designation from EmployeeInformation
                $q->orWhereHas('profile.employeeInformation', function ($q) {
                    $q->where('designation', 'like', '%OIC MARPO%');
                });
            })
            ->with('profile.employeeInformation')
            ->get()
            ->map(fn($u) => [
                'id'   => $u->id,
                'name' => $u->fullName
                    . ($u->profile?->position ? ' — ' . $u->profile->position : '')
                    . ($u->profile?->employeeInformation?->designation ? ' (' . $u->profile->employeeInformation->designation . ')' : ''),
            ])
            ->sortBy('name')
            ->values()
            ->toArray();
    }

    public function updatedUserId($value)
    {
        if (!$value) {
            return;
        }

        $user = User::with('profile')->find($value);
        if ($user && $user->profile) {
            $this->officer_name = $user->profile->first_name
                . ($user->profile->middle_name ? ' ' . $user->profile->middle_name : '')
                . ' ' . $user->profile->last_name;
            $this->academic_suffix = $user->profile->academic_suffix ?? null;
        }
    }

    public function create()
    {
        $this->reset(['station_code', 'officer_name', 'academic_suffix', 'user_id']);
        $this->editing = null;
        $this->modal = true;
    }

    public function edit(StationOfficer $officer)
    {
        $this->editing = $officer;
        $this->station_code = $officer->station_code;
        $this->officer_name = $officer->officer_name;
        $this->academic_suffix = $officer->academic_suffix;
        $this->user_id = $officer->user_id;
        $this->modal = true;
    }

    public function save()
    {
        $this->validate([
            'station_code'     => 'required|string|max:255',
            'officer_name'     => 'required|string|max:255',
            'academic_suffix'  => 'nullable|string|max:255',
            'user_id'          => 'nullable|exists:users,id',
        ]);

        $position = $this->resolvePosition();

        StationOfficer::updateOrCreate(
            ['id' => $this->editing?->id],
            [
                'station_code'     => $this->station_code,
                'officer_name'     => $this->officer_name,
                'academic_suffix'  => $this->academic_suffix ?: null,
                'position'         => $position,
                'user_id'          => $this->user_id ?: null,
            ]
        );

        $this->notification()->success(
            $this->editing ? 'Officer Updated' : 'Officer Created',
            $this->editing ? 'Record has been updated.' : 'New station officer has been added.'
        );

        $this->modal = false;
        $this->reset(['station_code', 'officer_name', 'academic_suffix', 'user_id']);
        $this->editing = null;
    }

    private function resolvePosition(): string
    {
        if ($this->user_id) {
            $user = User::with('profile.employeeInformation')->find($this->user_id);

            if ($user?->profile) {
                // Use profile position first, fall back to employee designation
                return $user->profile->position
                    ?? $user->profile->employeeInformation?->designation
                    ?? 'Unknown';
            }
        }

        return $this->officer_name ? 'Unknown' : '';
    }

    public function delete(StationOfficer $officer)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete Officer?',
            'description' => 'This will remove ' . $officer->officer_name . ' from ' . $officer->station_code . '.',
            'icon'        => 'trash',
            'accept'      => [
                'label'  => 'Delete',
                'color'  => 'negative',
                'method' => 'performDelete',
                'params' => $officer->id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performDelete(string $id)
    {
        StationOfficer::findOrFail($id)->delete();
        $this->notification()->success('Deleted', 'Station officer has been removed.');
    }
}; ?>

<x-main-container 
    title="Station Officers" 
    :breadcrumbs="[
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => 'Station Officers']
    ]"
>
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <x-button primary label="Add Officer" icon="plus" wire:click="create" />
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <x-input placeholder="Search by station or name..." icon="magnifying-glass" wire:model.live="search" />
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-secondary-900 shadow-md rounded-xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-secondary-50 dark:bg-secondary-800">
                <tr>
                    <th class="p-4">Station Code</th>
                    <th class="p-4">Officer Name</th>
                    <th class="p-4">Suffix</th>
                    <th class="p-4">Position</th>
                    <th class="p-4">Linked User</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary-200">
                @forelse($this->officers as $officer)
                    <tr>
                        <td class="p-4 font-bold">{{ $officer->station_code }}</td>
                        <td class="p-4">{{ $officer->officer_name }}</td>
                        <td class="p-4">{{ $officer->academic_suffix ?: '—' }}</td>
                        <td class="p-4">
                            @php
                                $ei = $officer->user?->profile?->employeeInformation;
                                $pos = PositionHelper::toAcronym($ei?->position ?? $officer->position ?? '');
                                $desig = $ei?->designation ? PositionHelper::toAcronym($ei->designation) : '';
                            @endphp
                            {{ $desig ? "{$pos}/{$desig}" : ($pos ?: '—') }}
                        </td>
                        <td class="p-4">
                            @if($officer->user)
                                <span class="badge badge-success badge-sm">{{ $officer->user->fullName }}</span>
                            @else
                                <span class="text-xs opacity-40">—</span>
                            @endif
                        </td>
                        <td class="p-4 flex justify-center gap-2">
                            <x-button rounded icon="pencil" wire:click="edit('{{ $officer->id }}')" />
                            <x-button rounded negative icon="trash"
                                wire:click="delete('{{ $officer->id }}')"
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center opacity-50">No station officers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $this->officers->links() }}</div>
    </div>

    {{-- Modal --}}
    <x-modal-card title="{{ $editing ? 'Edit' : 'Add' }} Station Officer" wire:model="modal">
        <div class="space-y-4">
            <x-input label="Station Code" wire:model.live="station_code" placeholder="e.g. DARMO-Nabunturan" />

            <x-select label="Assigned Officer" placeholder="Select user..." wire:model.live="user_id"
                :options="$this->eligibleUsers" option-label="name" option-value="id" searchable />

            <x-input label="Officer Name" wire:model="officer_name" placeholder="Auto-filled from selected user" />
            <x-input label="Academic Suffix (optional)" wire:model="academic_suffix" placeholder="e.g. MPA, MDMG, MExEd" />
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="Save" wire:click="save" spinner="save" />
            </div>
        </x-slot>
    </x-modal-card>
</x-main-container>
