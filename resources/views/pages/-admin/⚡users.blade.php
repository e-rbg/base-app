<?php

use Livewire\Component;
use App\Models\User;
use App\Models\UserProfile;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app', ['title' => 'Users'])] class extends Component
{
    use WithPagination, WireUiActions;
    
    public $search = '';
    public bool $userModal = false;

    // Form Properties
    public ?string $selected_id = null; // Null = Create, String = Edit
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $username = '';
    public string $email = '';
    public string $password = ''; // Only required on create

    // Reset pagination when search changes
    public function updatedSearch() { $this->resetPage(); }

    // --- Modal Logic ---
    public function openCreateModal()
    {
        $this->reset(['selected_id', 'first_name', 'middle_name', 'last_name', 'username', 'email', 'password']);
        $this->resetValidation();
        $this->userModal = true;
    }

    public function openEditModal(string $id)
    {
        $this->resetValidation();
        $user = User::with('profile')->findOrFail($id);
        
        $this->selected_id = $id;
        $this->first_name  = $user->profile->first_name;
        $this->middle_name = $user->profile->middle_name ?? '';
        $this->last_name   = $user->profile->last_name;
        $this->username    = $user->username;
        $this->email       = $user->email;
        $this->password    = ''; // Keep empty unless changing
        
        $this->userModal = true;
    }


    public function save()
    {
        $isEdit = !empty($this->selected_id);

        $rules = [
            'first_name' => 'required|min:2',
            'last_name'  => 'required|min:2',
            'username'   => 'required|alpha_dash|unique:users,username,' . $this->selected_id,
            'email'      => 'required|email|unique:users,email,' . $this->selected_id,
            'password'   => $isEdit ? 'nullable|min:8' : 'required|min:8',
        ];

        $this->validate($rules);

        DB::transaction(function () use ($isEdit) {
            if ($isEdit) {
                $user = User::findOrFail($this->selected_id);
                $user->update([
                    'username' => $this->username,
                    'email'    => $this->email,
                ]);
                if ($this->password) {
                    $user->update(['password' => Hash::make($this->password)]);
                }
                $user->profile->update([
                    'first_name'  => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name'   => $this->last_name,
                ]);
            } else {
                $user = User::create([
                    'username' => $this->username,
                    'email'    => $this->email,
                    'password' => Hash::make($this->password),
                ]);
                $user->profile()->create([
                    'first_name'  => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name'   => $this->last_name,
                ]);
            }
        });

        $this->userModal = false;
        $this->notification()->success(
            $isEdit ? 'User Updated' : 'User Created',
            "Changes for {$this->first_name} have been saved."
        );
    }

    public function deleteUser(string $id)
    {
        $this->dialog()->confirm([
            'title'       => 'Are you sure?',
            'description' => 'This action will permanently delete this user.',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete them',
                'method' => 'executeDelete',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'Cancel',
            ],
        ]);
    }

    public function executeDelete(string $id)
    {
        User::findOrFail($id)->delete();
        $this->notification()->success('User Deleted', 'The account has been removed.');
    }

    public function with(): array
    {
        return [
            'users' => User::query()
                ->with('profile')
                ->where(function ($query) {
                    $query->where('email', 'like', "%{$this->search}%")
                        ->orWhere('username', 'like', "%{$this->search}%")
                        ->orWhereHas('profile', fn($q) => $q->where('first_name', 'like', "%{$this->search}%")->orWhere('last_name', 'like', "%{$this->search}%"));
                })->latest()->paginate(10),
        ];

        // OR return [
        //        'users' => User::with('profile')->search($this->search)->latest()->paginate(10),
        // ];
    }
    

    /**
     * Toggle User Status (Active/Suspended)
     */
    public function toggleStatus(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Switch between active and suspended
        $newStatus = ($user->status === 'active') ? 'suspended' : 'active';
        
        $user->update([
            'status' => $newStatus
        ]);

        // WireUI v2 notification
        $this->notification()->info(
            title: 'Status Updated',
            description: "User is now {$newStatus}."
        );
    }

  
}; ?>

<x-main-container 
    title="Users" 
    :breadcrumbs="[
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => 'Users']
    ]"
>
    <div class="grid grid-cols-1 gap-6">
        <div class="space-y-6">
            {{-- 1. HEADER & SEARCH --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-1">
                <div>
                    <h2 class="text-2xl font-black text-base-content tracking-tight">List of Users</h2>
                    <p class="text-xs font-bold uppercase tracking-widest opacity-40">Manage Users Credetials</p>
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="flex-1 sm:w-64">
                        <x-input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search..." shadow="none" />
                    </div>
                    {{-- v2: icon is simply passed as a string --}}
                    <x-mini-button primary icon="plus" rounded wire:click="openCreateModal" />
                </div>
            </div>

            {{-- 1. MOBILE VIEW (Visible on < 768px) --}}
            <div class="grid grid-cols-1 gap-3 md:hidden">
                @forelse($users as $user)
                    <div class="bg-base-100 border border-base-200 rounded-2xl p-4 shadow-sm active:bg-base-200/50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <x-avatar size="w-12 h-12" rounded="lg" src="https://ui-avatars.com/api/?name={{ urlencode($user->profile->full_name ?? $user->username) }}&background=random" />
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold leading-tight text-base-content">{{ $user->initialed_name }}</span>
                                    <span class="text-[11px] opacity-50 lowercase">{{ $user->email }}</span>
                                    <span class="text-[10px] font-medium text-primary/70 mt-0.5">@ {{ $user->username }}</span>
                                </div>
                            </div>
                            {{-- Status Badge --}}
                            @if($user->status === 'active')
                                <x-badge flat positive label="Active" class="text-[9px] font-black uppercase" />
                            @else
                                <x-badge flat warning label="Suspended" class="text-[9px] font-black uppercase" />
                            @endif
                        </div>

                        {{-- Mobile Action Buttons --}}
                        <div class="mt-4 flex justify-end gap-2 border-t border-base-200 pt-3">
                            <x-button xs secondary flat icon="pencil-square" label="Edit" wire:click="openEditModal('{{ $user->id }}')" />
                            <x-button xs :warning="$user->status === 'active'" flat icon="no-symbol" label="Status" wire:click="toggleStatus('{{ $user->id }}')" />
                            <x-button xs negative flat icon="trash" label="Delete" wire:click="deleteUser('{{ $user->id }}')" />
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center opacity-30 font-bold uppercase tracking-widest text-xs">No Members Found</div>
                @endforelse
            </div>

            {{-- 2. DESKTOP VIEW (Visible on >= 768px) --}}
            <div class="hidden md:block bg-base-100 border border-base-200 rounded-[2rem] overflow-hidden shadow-sm">
                <table class="table table-lg w-full">
                    <thead class="bg-base-200/50 text-base-content/50">
                        <tr>
                            <th class="text-[10px] uppercase tracking-widest font-black py-6 pl-10">User Info</th>
                            <th class="text-[10px] uppercase tracking-widest font-black py-6">Username</th>
                            <th class="text-[10px] uppercase tracking-widest font-black py-6">Status</th>
                            <th class="text-[10px] uppercase tracking-widest font-black py-6 text-right pr-10">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-200/50">
                        @foreach($users as $user)
                            <tr class="group hover:bg-base-200/30 transition-colors">
                                <td class="py-5 pl-10">
                                    <div class="flex items-center gap-4">
                                        <x-avatar size="w-10 h-10" rounded="lg" src="https://ui-avatars.com/api/?name={{ urlencode($user->profile->full_name ?? $user->username) }}&background=random" />
                                        <div class="flex flex-col">
                                            <span class="font-bold text-sm text-base-content">{{ $user->profile->first_name . ' ' . $user->profile->middle_name . ' ' . $user->profile->last_name }}</span>
                                            <span class="text-xs opacity-40 lowercase">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs font-medium opacity-60">@ {{ $user->username }}</td>
                                <td>
                                    @if($user->status === 'active')
                                        <x-badge flat positive label="Active" class="text-[10px] font-bold uppercase" />
                                    @else
                                        <x-badge flat warning label="Suspended" class="text-[10px] font-bold uppercase" />
                                    @endif
                                </td>
                                <td class="text-right pr-10">
                                    <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <x-mini-button rounded secondary flat icon="pencil-square" wire:click="openEditModal('{{ $user->id }}')" />
                                        <x-mini-button rounded :warning="$user->status === 'active'" flat icon="no-symbol" wire:click="toggleStatus('{{ $user->id }}')" />
                                        <x-mini-button rounded negative flat icon="trash" wire:click="deleteUser('{{ $user->id }}')" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 px-2">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- --- THE MODAL (WireUI v2) --- --}}
    {{-- v2: wire:model replaces wire:model.defer; title attribute is standard --}}
    <x-modal-card title="{{ $selected_id ? 'Edit User' : 'Add New User' }}" wire:model="userModal" z-index="z-50">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-input label="First Name" wire:model="first_name" />
            <x-input label="Last Name" wire:model.model="last_name" />
            
            <div class="sm:col-span-2">
                <x-input label="Middle Name (Optional)" wire:model="middle_name" />
            </div>

            <x-input label="Username" prefix="@" wire:model="username" />
            <x-input label="Email Address" icon="envelope" wire:model="email" />

            <div class="sm:col-span-2">
                <x-password label="{{ $selected_id ? 'New Password (Leave blank to keep current)' : 'Password' }}" 
                            wire:model="password" />
            </div>
        </div>

        <x-slot name="footer" class="flex items-center justify-end gap-x-3">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button primary label="Save User" wire:click="save" spinner="save" />
        </x-slot>
    </x-modal-card>
</x-main-container>