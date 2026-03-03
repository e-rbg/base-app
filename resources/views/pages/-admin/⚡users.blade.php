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
    public ?string $selected_id = null;
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'user'; // New Property

    public bool $showAvatarModal = false;
    public ?string $avatarUrl = null;


    public function updatedSearch() { $this->resetPage(); }

    public function openCreateModal()
    {
        $this->reset(['selected_id', 'first_name', 'middle_name', 'last_name', 'username', 'email', 'password', 'role']);
        $this->role = 'user'; // Default
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
        $this->role        = $user->role ?? 'user'; // Load Role
        $this->password    = '';       
        $this->userModal = true;
        
    }

    public function save()
    {
        $isEdit = !empty($this->selected_id);
        $isSuperAdmin = auth()->user()->role === 'super_admin';

        $rules = [
            'first_name' => 'required|min:2',
            'last_name'  => 'required|min:2',
            'username'   => 'required|alpha_dash|unique:users,username,' . $this->selected_id,
            'email'      => 'required|email|unique:users,email,' . $this->selected_id,
        ];

        // Only apply password and role rules if Super Admin
        if ($isSuperAdmin) {
            $rules['role'] = 'required|in:super_admin,admin,editor,user';
            $rules['password'] = $isEdit ? 'nullable|min:8' : 'required|min:8';
        }

        $this->validate($rules);

        DB::transaction(function () use ($isEdit, $isSuperAdmin) {
            $userData = [
                'username' => $this->username,
                'email'    => $this->email,
            ];

            // SECURITY: Only update Role and Password if requester is Super Admin
            if ($isSuperAdmin) {
                $userData['role'] = $this->role;
                
                if (!empty($this->password)) {
                    $userData['password'] = Hash::make($this->password);
                }
            }

            if ($isEdit) {
                $user = User::findOrFail($this->selected_id);
                $user->update($userData);
                $user->profile->update([
                    'first_name'  => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name'   => $this->last_name,
                ]);
            } else {
                // If creating, the system still needs a role/password. 
                // We ensure it falls back to 'user' and a random password 
                // if for some reason a non-super-admin is creating (though you should block the whole page).
                if (!$isSuperAdmin) {
                    $userData['role'] = 'user';
                    $userData['password'] = Hash::make(Str::random(12));
                }

                $user = User::create($userData);
                $user->profile()->create([
                    'first_name'  => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name'   => $this->last_name,
                ]);
            }
        });

        $this->userModal = false;
        $this->notification()->success($isEdit ? 'User Updated' : 'User Created');
    }

    public function deleteUser(string $id)
    {
        // Prevent deleting yourself
        if ($id == auth()->id()) {
            return $this->notification()->error('Action Denied', 'You cannot delete your own account.');
        }

        $this->dialog()->confirm([
            'title'       => 'Are you sure?',
            'description' => 'This action will permanently delete this user.',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete them',
                'method' => 'executeDelete',
                'params' => $id,
            ],
        ]);
    }

    public function executeDelete(string $id)
    {
        User::findOrFail($id)->delete();
        $this->notification()->success('User Deleted');
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
    }

    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);
        $newStatus = ($user->status === 'active') ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);
        $this->notification()->info(title: 'Status Updated', description: "User is now {$newStatus}.");
    }

    public function viewAvatar(?string $url)
    {
        $this->avatarUrl = $url;
        $this->showAvatarModal = true;
    }

    public function closeAvatarModal()
    {
        $this->avatarUrl = null;
        $this->showAvatarModal = false;
    }


}; ?>

<x-main-container title="Users" :breadcrumbs="[['url' => route('admin.dashboard'), 'label' => 'Dashboard'], ['label' => 'Users']]">
    <div class="space-y-6">
        {{-- HEADER & SEARCH --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-1">
            <div>
                <h2 class="text-2xl font-black text-base-content tracking-tight">User Directory</h2>
                <p class="text-xs font-bold uppercase tracking-widest opacity-40">Manage Roles & Access</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="flex-1 sm:w-64">
                    <x-input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Search users..." shadow="none" />
                </div>
                <x-button primary icon="plus" label="Add User" rounded wire:click="openCreateModal" />
            </div>
        </div>

        {{-- DESKTOP VIEW --}}
        <div class="hidden md:block bg-base-100 border border-base-200 rounded-[2rem] overflow-hidden shadow-sm">
            <table class="table table-lg w-full">
                <thead class="bg-base-200/50">
                    <tr>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6 pl-10">Name</th>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6">Role</th>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6">Status</th>
                        <th class="text-[10px] uppercase tracking-widest font-black py-6 text-right pr-10">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200/50">
                    @foreach($users as $user)
                        <tr class="group hover:bg-base-200/30 transition-colors">
                            <td class="py-5 pl-10">
                                <div class="flex items-center gap-4">
                                    @php
                                        $avatarUrl = $user->profile?->avatar 
                                            ? asset('storage/' . $user->profile->avatar) 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->fullname) . '&background=random';
                                    @endphp

                                    {{-- Trigger: Dispatches event to the Global SFC --}}
                                    <div class="cursor-zoom-in hover:scale-110 transition-transform active:scale-95"
                                        @click="$dispatch('preview-image', { url: '{{ $avatarUrl }}', title: '{{ $user->fullname }}' })">
                                        
                                        <x-avatar 
                                            size="w-10 h-10" 
                                            rounded="lg" 
                                            :src="$avatarUrl" 
                                        />
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm leading-none">{{ $user->fullname }}</span>
                                        <span class="text-xs opacity-40 mt-1">@ {{ $user->username }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $roleColor = match($user->role) {
                                        'super_admin' => 'negative',
                                        'admin' => 'primary',
                                        'editor' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-badge flat :color="$roleColor" label="{{ str_replace('_', ' ', strtoupper($user->role)) }}" class="text-[9px] font-black" />
                            </td>
                            <td>
                                <x-badge flat :positive="$user->status === 'active'" :warning="$user->status !== 'active'" label="{{ $user->status }}" class="text-[9px] font-black uppercase" />
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
        {{-- 1. MOBILE VIEW (Visible on < 768px) --}}
        <div class="grid grid-cols-1 gap-4 md:hidden px-1">
            @forelse($users as $user)
                <div class="bg-base-100 border border-base-200 rounded-[2rem] p-5 shadow-sm active:bg-base-200/50 transition-all">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-4">
                            @php
                                $avatarUrl = $user->profile?->avatar 
                                    ? asset('storage/' . $user->profile->avatar) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->fullname) . '&background=random';
                            @endphp
                            <div class="cursor-zoom-in hover:scale-110 transition-transform active:scale-95"
                                @click="$dispatch('preview-image', { url: '{{ $avatarUrl }}', title: '{{ $user->fullname }}' })">
                                
                                <x-avatar 
                                    size="w-10 h-10" 
                                    rounded="lg" 
                                    :src="$avatarUrl" 
                                />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-base font-black leading-tight text-base-content">{{ $user->full_name }}</span>
                                <span class="text-xs opacity-50 lowercase">{{ $user->email }}</span>
                                
                                {{-- Username & Status Row --}}
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-[10px] font-bold text-primary uppercase tracking-tighter">@ {{ $user->username }}</span>
                                    <span class="opacity-20">•</span>
                                    <x-badge 
                                        flat 
                                        :positive="$user->status === 'active'" 
                                        :warning="$user->status !== 'active'" 
                                        label="{{ $user->status }}" 
                                        class="text-[8px] font-black uppercase px-1.5 h-4" 
                                    />
                                </div>
                            </div>
                        </div>

                        {{-- Role Badge (Mobile Top-Right) --}}
                        @php
                            $roleColor = match($user->role) {
                                'super_admin' => 'negative',
                                'admin'       => 'primary',
                                'editor'      => 'info',
                                default       => 'secondary'
                            };
                        @endphp
                        <x-badge flat :color="$roleColor" label="{{ str_replace('_', ' ', strtoupper($user->role)) }}" class="text-[8px] font-black" />
                    </div>

                    {{-- Mobile Action Buttons --}}
                    <div class="mt-5 flex justify-between items-center border-t border-base-200 pt-4">
                        <div class="flex gap-1">
                            <x-button xs secondary flat icon="pencil-square" label="Edit" wire:click="openEditModal('{{ $user->id }}')" />
                            <x-button xs :warning="$user->status === 'active'" flat icon="no-symbol" label="Status" wire:click="toggleStatus('{{ $user->id }}')" />
                        </div>
                        
                        <x-button xs negative flat icon="trash" label="Delete" wire:click="deleteUser('{{ $user->id }}')" />
                    </div>
                </div>
            @empty
                <div class="p-10 text-center bg-base-100 border-2 border-dashed border-base-200 rounded-[2rem]">
                    <x-icon name="users" class="w-10 h-10 mx-auto opacity-20 mb-2" />
                    <p class="text-xs font-bold uppercase tracking-widest opacity-30">No User Found</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>

    {{-- MODAL --}}
    <x-modal-card title="{{ $selected_id ? 'Edit User Profile' : 'Create New Account' }}" wire:model="userModal" z-index="z-50" rounded="3xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            
            {{-- 1. Name Fields --}}
            <x-input label="First Name" wire:model="first_name" placeholder="John" />
            <x-input label="Last Name" wire:model="last_name" placeholder="Doe" />
            
            <div class="sm:col-span-2">
                <x-input label="Middle Name (Optional)" wire:model="middle_name" />
            </div>

            {{-- 2. Credentials --}}
            <x-input label="Username" prefix="@" wire:model="username" />
            <x-input label="Email Address" icon="envelope" wire:model="email" />

            {{-- 3. Restricted Section (Role & Password) --}}
            <div class="sm:col-span-2 space-y-5 border-t border-base-200 pt-5 mt-2">
                @php $isSuperAdmin = auth()->user()->role === 'super_admin'; @endphp

                {{-- Role Selection --}}
                <div class="relative group">
                    <x-select
                        label="Account Role"
                        placeholder="Select role"
                        wire:model="role"
                        :options="[
                            ['name' => 'Super Admin', 'id' => 'super_admin'],
                            ['name' => 'Admin', 'id' => 'admin'],
                            ['name' => 'Editor', 'id' => 'editor'],
                            ['name' => 'User', 'id' => 'user'],
                        ]"
                        option-label="name"
                        option-value="id"
                        :disabled="!$isSuperAdmin"
                    />
                    @if(!$isSuperAdmin)
                        <div class="mt-1 flex items-center gap-1 text-amber-600 dark:text-amber-500">
                            <x-icon name="shield-exclamation" class="w-3.5 h-3.5" />
                            <p class="text-[10px] font-bold tracking-tight">Role management restricted.</p>
                        </div>
                        <div class="absolute inset-0 cursor-not-allowed" title="Super Admin Only"></div>
                    @endif
                </div>

                {{-- Restricted Password Field --}}
                <div class="relative group border-t border-base-200 pt-4">
                    <x-password 
                        label="{{ $selected_id ? 'Change Password' : 'Set Password' }}" 
                        wire:model="password" 
                        placeholder="••••••••"
                        :disabled="!$isSuperAdmin"
                    />
                    
                    @if($isSuperAdmin)
                        @if($selected_id)
                            <p class="mt-1 text-[10px] opacity-50 italic">Leave blank to keep current password.</p>
                        @endif
                    @else
                        <div class="mt-1 flex items-center gap-1 text-amber-600 dark:text-amber-500">
                            <x-icon name="lock-closed" class="w-3.5 h-3.5" />
                            <p class="text-[10px] font-bold tracking-tight">Password changes restricted.</p>
                        </div>
                        <div class="absolute inset-0 cursor-not-allowed" title="Super Admin Only"></div>
                    @endif
                </div>
            </div>
        </div>

        <x-slot name="footer" class="flex items-center justify-end gap-x-3">
            <x-button flat label="Cancel" x-on:click="close" class="font-bold" />
            <x-button primary label="Save Account" wire:click="save" spinner="save" class="font-bold px-6" />
        </x-slot>
    </x-modal-card>

    
    
    
</x-main-container>