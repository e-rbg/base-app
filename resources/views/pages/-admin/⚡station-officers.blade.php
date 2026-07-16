<?php

use App\Models\StationOfficer;
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
    public string $position = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\Computed]
    public function officers()
    {
        return StationOfficer::query()
            ->when($this->search, fn($q) => $q->where('station_code', 'like', "%{$this->search}%")
                ->orWhere('officer_name', 'like', "%{$this->search}%"))
            ->ordered()
            ->paginate(15);
    }

    public function create()
    {
        $this->reset(['station_code', 'officer_name', 'position']);
        $this->editing = null;
        $this->modal = true;
    }

    public function edit(StationOfficer $officer)
    {
        $this->editing = $officer;
        $this->station_code = $officer->station_code;
        $this->officer_name = $officer->officer_name;
        $this->position = $officer->position;
        $this->modal = true;
    }

    public function save()
    {
        $this->validate([
            'station_code' => 'required|string|max:255',
            'officer_name' => 'required|string|max:255',
            'position'     => 'required|string|max:255',
        ]);

        StationOfficer::updateOrCreate(
            ['id' => $this->editing?->id],
            [
                'station_code' => $this->station_code,
                'officer_name' => $this->officer_name,
                'position'     => $this->position,
            ]
        );

        $this->notification()->success(
            $this->editing ? 'Officer Updated' : 'Officer Created',
            $this->editing ? 'Record has been updated.' : 'New station officer has been added.'
        );

        $this->modal = false;
        $this->reset(['station_code', 'officer_name', 'position']);
        $this->editing = null;
    }

    public function delete(StationOfficer $officer)
    {
        $officer->delete();

        $this->notification()->success('Deleted', 'Station officer has been removed.');
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Station Officers</h1>
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
                    <th class="p-4">Position</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary-200">
                @forelse($this->officers as $officer)
                    <tr>
                        <td class="p-4 font-bold">{{ $officer->station_code }}</td>
                        <td class="p-4">{{ $officer->officer_name }}</td>
                        <td class="p-4">{{ $officer->position }}</td>
                        <td class="p-4 flex justify-center gap-2">
                            <x-button rounded icon="pencil" wire:click="edit('{{ $officer->id }}')" />
                            <x-button rounded negative icon="trash"
                                x-on:confirm="{
                                    title: 'Delete Officer?',
                                    description: 'This will remove the assigned officer for this station.',
                                    method: 'delete',
                                    params: '{{ $officer->id }}'
                                }"
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center opacity-50">No station officers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $this->officers->links() }}</div>
    </div>

    {{-- Modal --}}
    <x-modal-card title="{{ $editing ? 'Edit' : 'Add' }} Station Officer" wire:model="modal">
        <div class="space-y-4">
            <x-input label="Station Code" wire:model="station_code" placeholder="e.g. DARMO-Nabunturan" />
            <x-input label="Officer Name" wire:model="officer_name" placeholder="e.g. Juan Dela Cruz" />
            <x-input label="Position" wire:model="position" placeholder="e.g. MARPO" />
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="Save" wire:click="save" spinner="save" />
            </div>
        </x-slot>
    </x-modal-card>
</div>
